<?php

namespace app\models;

use yii\base\InvalidConfigException;

class Author extends \yii\db\ActiveRecord
{
    public static function tableName(): string
    {
        return 'author';
    }

    public function rules(): array
    {
        return [['name', 'required'], ['name', 'string', 'max' => 255]];
    }

    /**
     * @throws InvalidConfigException
     */
    public function getBooks()
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])
            ->viaTable('book_author', ['author_id' => 'id']);
    }
}
