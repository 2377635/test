<?php

use yii\helpers\Html;

/* @var $document \app\models\Document */
/* @var $attachments \app\models\Attachments[] */

?>

<div class="documents-view row">
    <div class="col-lg-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($document->title); ?></h3>
            </div>
            <div class="panel-body"><?= Html::encode($document->description); ?></div>
        </div>
        <?= Html::a('Вернуться', ['documents/index'], ['class' => 'btn btn-info']); ?>
    </div>
    
    <div class="col-lg-4">
        <table class="table table-striped">
            <?php foreach ($attachments as $attachment) { ?>
            <tr>
                <td>
                    <?= $attachment->origname; ?>
                </td>
                <td>
                    <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['attachments/view', 'id' => $attachment->id], ['target' => '_blank']); ?>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
    
</div>
