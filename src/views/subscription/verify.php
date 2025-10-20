<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\VerifyForm $model */
/** @var app\models\Subscriber $subscriber */
/** @var int $book_id */

$this->title = 'Подтверждение телефона';
?>
<div class="subscription-verify">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>На номер <?= Html::encode($subscriber->phone) ?> отправлен код подтверждения.</p>

    <?php if (YII_ENV_DEV): ?>
        <p><strong>DEV:</strong> Код подтверждения: <?= Html::encode($subscriber->verification_code) ?></p>
    <?php endif; ?>

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'code')->textInput(['placeholder' => 'Введите код']) ?>

    <?= Html::hiddenInput('book_id', $book_id) ?>
    <div class="form-group">
        <?= Html::submitButton('Подтвердить', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
