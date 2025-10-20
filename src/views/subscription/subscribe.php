<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SubscriberForm $model */
/** @var app\models\Author $author */
/** @var int $book_id */

$this->title = "Подписка на автора: " . $author->name;
?>
<div class="subscription-subscribe">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Введите номер телефона, чтобы подписаться на уведомления о новых книгах.</p>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'phone')->textInput(['placeholder' => '+79991234567']) ?>

    <?= $form->field($model, 'book_id')->hiddenInput(['value' => $book_id])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Подписаться', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
`