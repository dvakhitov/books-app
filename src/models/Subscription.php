<?php

namespace app\models;

use yii\db\ActiveRecord;

final class Subscription extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'subscription';
    }

    public function rules(): array
    {
        return [
            [['phone', 'author_id'], 'required'],
            ['phone', 'match', 'pattern' => '/^\+?[0-9]{10,15}$/'],
            [['author_id'], 'integer'],
            [['confirmation_code'], 'string', 'max' => 10],
            [['confirmed_at'], 'safe'],
            [['phone', 'author_id'], 'unique', 'targetAttribute' => ['phone', 'author_id']],
        ];
    }

    public function isConfirmed(): bool
    {
        return $this->confirmed_at !== null;
    }
}
