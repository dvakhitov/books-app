<?php

namespace app\models;

use yii\base\Model;

final class SubscriberForm extends Model
{
    public ?string $phone = null;
    public ?int $book_id  = null;

    public function rules(): array
    {
        return [
            ['phone', 'required'],
            ['phone', 'match', 'pattern' => '/^\+7\d{10}$/', 'message' => 'Введите телефон в формате +7XXXXXXXXXX'],
            ['book_id', 'integer'],
        ];
    }
}
