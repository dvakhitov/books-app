<?php

namespace app\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

final class Author extends ActiveRecord
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
