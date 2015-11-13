<?php

use yii\grid\GridView;
use yii\helpers\Html;


/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<div class="document-index">
    <div class="form-group">
        <?= Html::a('Создать', ['documents/create'], ['class' => 'btn btn-sm btn-primary']); ?>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'title',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]) ?>
</div>