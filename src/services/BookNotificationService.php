<?php

namespace app\services;

use app\models\Book;
use app\models\Subscriber;
use app\models\Subscription;

final readonly class BookNotificationService
{
    public function __construct(private SmsService $smsService = new SmsService())
    {
    }

    public function notifyAboutNewBook(Book $book): void
    {
        $authors = method_exists($book, 'getAuthors') ? $book->authors : [];
        if (empty($authors)) {
            return;
        }

        $authorIds = array_map(static fn ($a) => $a->id, $authors);

        $subscriptions = Subscription::find()
            ->where(['author_id' => $authorIds])
            ->all();

        if (!$subscriptions) {
            return;
        }

        $phones = [];
        foreach ($subscriptions as $subscription) {
            /** @var Subscription $subscription */
            $subscriber = Subscriber::findOne($subscription->subscriber_id);
            if ($subscriber && (int)$subscriber->is_verified === 1 && !empty($subscriber->phone)) {
                $phones[$subscriber->phone] = true;
            }
        }

        if (!$phones) {
            return;
        }

        $message = "Новая книга: {$book->title}";
        foreach (array_keys($phones) as $phone) {
            $this->smsService->send($phone, $message);
        }
    }
}
