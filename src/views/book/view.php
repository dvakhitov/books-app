<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Book $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить эту книгу?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <h3>Авторы</h3>
    <div style="max-width:400px">
        <?php
        if (!empty($model->authors)): ?>
            <ul class="list-unstyled">
                <?php
                foreach ($model->authors as $author): ?>
                    <li class="d-flex align-items-center justify-content-between mb-2">
                        <span><?= Html::encode($author->name) ?></span>
                        <span>
                        <?= Html::a(
                            'Подписаться на автора',
                            [
                                'subscription/subscribe',
                                'author_id' => $author->id,
                                'book_id' => $model->id
                            ],
                            ['class' => 'btn btn-success']
                        ) ?>

                            </span>
                    </li>
                <?php
                endforeach; ?>
            </ul>
        <?php
        else: ?>
            <p><em>Авторы не указаны</em></p>
        <?php
        endif; ?>
    </div>
    <hr>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'year',
            'description:ntext',
            'isbn',
            [
                'attribute' => 'cover',
                'format' => ['image', ['width' => '150', 'height' => '200']],
            ],
            'created_at',
        ],
    ]) ?>

</div>
