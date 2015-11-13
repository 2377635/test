<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\web\View;

use app\assets\DropzoneAsset;

/* @var $this \yii\web\View */
/* @var $document \app\models\Document */

DropzoneAsset::register($this);

?>

<div class="document-form row">
    <div class="col-lg-8">
        <?php $form = ActiveForm::begin(); ?>
        
        <?= $form->field($document, 'title')->textInput(); ?>
        
        <?= $form->field($document, 'description')->textarea(); ?>
        
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-sm']) ?>
            <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-info btn-sm']); ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-lg-4">
        <form id="upload-form" action="<?= Url::to(['attachments/upload', 'documentID' => $document->id]); ?>" class="dropzone">
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken(); ?>" />
        </form>
        <div id="attachments"></div>
    </div>
</div>

<?php
$url = Url::to(['attachments/index', 'documentID' => $document->id]);

$this->registerJs(
<<<JS
function loadAttachments()
{
    $("#attachments").load("$url");
}
JS
, View::POS_HEAD);

$this->registerJs(
<<<JS
Dropzone.options.uploadForm = {
    init: function() {
        this.on("success", function(file, obj) {
            if (obj.success === true) {
                loadAttachments();
            }
        });
    }
};
JS
, View::POS_END);

$this->registerJs(
<<<JS
loadAttachments();
JS
, View::POS_READY);