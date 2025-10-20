<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

final class AuthorSubscription extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'author_subscription';
    }

    public function rules(): array
    {
        return [['author_id', 'phone', 'required'], ['phone', 'string', 'max' => 32]];
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
