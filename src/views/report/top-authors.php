<?php

use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\data\ArrayDataProvider $dataProvider */
/** @var int $year  */

$this->title = 'ТОП-10 авторов за ' . $year;
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'name',
        'cnt',
    ],
]) ?>