<?php

namespace app\models;

use yii\db\ActiveRecord;

final class BookAuthor extends ActiveRecord
{
    public static function tableName()
    {
        return 'book_author';
    }
}
