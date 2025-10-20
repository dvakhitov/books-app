<?php

namespace app\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

final class Book extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'book';
    }

    public function rules(): array
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['year'], 'integer'],
            [['title', 'isbn', 'cover'], 'string', 'max' => 255],
        ];
    }

    /**
     * @throws InvalidConfigException
     */
    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('book_author', ['book_id' => 'id']);
    }
}
