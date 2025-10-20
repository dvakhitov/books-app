<?php

namespace app\models;

use yii\base\Model;

class VerifyForm extends Model
{
    public ?string $code = null;
    public ?int $book_id = null;

    public function rules(): array
    {
        return [
            ['code', 'required'],
        ];
    }
}
