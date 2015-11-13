<?php

use yii\helpers\Html;

/* @var $document \app\models\Document */
/* @var $attachments \app\models\Attachments[] */

?>

<div class="documents-view">
    <h3><?= Html::encode($document->title); ?></h3>
    <div class="description"><?= Html::encode($document->description); ?></div>
    <div>
        <h5>Файлы</h5>
        <ul>
        <?php foreach ($attachments as $attachment) { ?>
            <li><?= Html::a($attachment->origname, ['attachments/view', 'id' => $attachment->id]); ?></li>
        <?php } ?>
        </ul>
    </div>
    <?= Html::a('Вернуться', ['documents/index'], ['class' => 'btn btn-info']); ?>
</div>
