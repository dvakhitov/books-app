<?php

namespace app\services;

use app\models\Author;
use app\models\Subscriber;
use app\models\Subscription;
use Throwable;
use Yii;

final readonly class SubscriptionService
{
    public function __construct(private SmsService $smsService = new SmsService())
    {
    }

    /**
     * Старт подписки: создать/найти подписчика, проверить существующую подписку, отправить код.
     * Подписка НЕ создаётся на этом этапе.
     * Возвращает: ['subscriber' => Subscriber, 'existed' => bool]
     */
    public function start(Author $author, string $phone): array
    {
        $subscriber = Subscriber::findOne(['phone' => $phone]);
        if ($subscriber === null) {
            $subscriber = new Subscriber([
                'phone'             => $phone,
                'verification_code' => rand(1000, 9999),
                'is_verified'       => 0,
            ]);
            $subscriber->save(false);
        }

        $exists = Subscription::find()
            ->where(['author_id' => $author->id, 'subscriber_id' => $subscriber->id])
            ->exists();
        if ($exists) {
            return [
                'subscriber' => $subscriber,
                'existed'    => true,
            ];
        }

        $text = "Вы подписались на автора {$author->name}. Код подтверждения: {$subscriber->verification_code}";
        $this->smsService->send($subscriber->phone, $text);

        return [
            'subscriber' => $subscriber,
            'existed'    => false,
        ];
    }

    /**
     * Подтвердить подписку: создать запись подписки, если её ещё нет.
     * @param Author $author
     * @param Subscriber $subscriber
     * @return void
     * @throws Throwable
     * @throws \yii\db\Exception
     */
    public function confirm(Author $author, Subscriber $subscriber): void
    {
        $exists = Subscription::find()
            ->where(['author_id' => $author->id, 'subscriber_id' => $subscriber->id])
            ->exists();
        if ($exists) {
            return;
        }

        $db          = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $subscription = new Subscription([
                'subscriber_id' => $subscriber->id,
                'author_id'     => $author->id,
            ]);
            $subscription->save(false);
            $transaction->commit();
        } catch (Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
