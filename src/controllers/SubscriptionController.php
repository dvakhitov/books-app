<?php

namespace app\controllers;

use app\models\Author;
use app\models\Subscriber;
use app\models\SubscriberForm;
use app\services\SmsService;
use app\services\SubscriptionService;
use Yii;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use \app\models\VerifyForm;

class SubscriptionController extends Controller
{
    private SmsService $smsService;
    private SubscriptionService $subscriptionService;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->smsService          = new SmsService();
        $this->subscriptionService = new SubscriptionService($this->smsService);
    }

    /**
     * @throws NotFoundHttpException
     */
    private function findAuthorOrFail(int $authorId): Author
    {
        $author = Author::findOne($authorId);
        if (!$author) {
            throw new NotFoundHttpException('Автор не найден.');
        }

        return $author;
    }

    /**
     * @throws Exception
     * @throws NotFoundHttpException|\Throwable
     */
    public function actionSubscribe(int $author_id, int $book_id): \yii\web\Response|string
    {
        $author = $this->findAuthorOrFail($author_id);

        $model          = new SubscriberForm();
        $model->book_id = $book_id;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $result     = $this->subscriptionService->start($author, $model->phone);
            $subscriber = $result['subscriber'];
            if ($result['existed'] === true) {
                Yii::$app->session->setFlash('info', 'Подписка уже существует.');

                return $this->redirect(['verify', 'phone' => $subscriber->phone, 'book_id' => $model->book_id, 'author_id' => $author->id]);
            }

            Yii::$app->session->setFlash('success', 'Проверьте SMS с кодом подтверждения.');

            return $this->redirect(['verify', 'phone' => $subscriber->phone, 'book_id' => $model->book_id, 'author_id' => $author->id]);
        }

        return $this->render('subscribe', [
            'model'   => $model,
            'author'  => $author,
            'book_id' => $book_id,
        ]);
    }

    /**
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionVerify(string $phone, $book_id, ?int $author_id = null): \yii\web\Response|string
    {
        $subscriber = Subscriber::findOne(['phone' => $phone]);
        if (!$subscriber) {
            throw new NotFoundHttpException('Подписчик не найден.');
        }
        $model = new VerifyForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->code == $subscriber->verification_code) {
                $subscriber->is_verified = 1;
                $subscriber->save(false);
                Yii::$app->session->setFlash('success', 'Телефон успешно подтверждён! Подписка оформлена.');
                if ($author_id !== null) {
                    $author = $this->findAuthorOrFail($author_id);
                    $this->subscriptionService->confirm($author, $subscriber);
                }

                return $this->redirect(['book/view', 'id' => $book_id]);
            } else {
                Yii::$app->session->setFlash('error', 'Неверный код.');
            }
        }

        return $this->render('verify', [
            'model'      => $model,
            'subscriber' => $subscriber,
            'book_id'    => $book_id,
        ]);
    }

}
