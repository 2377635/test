<?php

use yii\helpers\Url;
use app\assets\JuiAsset;

/* @var $this \yii\web\View */

JuiAsset::register($this);

$items = [];
?>


<ul id="sortable" style="list-style:none;margin:10px 0 0;padding:0;">
<?php foreach ($attachments as $item) { ?>
    <li class="ui-state-default" id="attachment-<?= $item->id; ?>" data-id="<?= $item->id; ?>" style="padding:5px;">
    <div style="display:block;float:left;"><?= $item->origname; ?></div>
    <div style="float:right">
        <a href="<?= Url::to(['attachments/view', 'id' => $item->id]); ?>" target="_blank">
            <span class="glyphicon glyphicon-eye-open"></span>
        </a>
        <a class="update-btn" href="<?= Url::to(['attachments/get', 'id' => $item->id]); ?>">
            <span class="glyphicon glyphicon-pencil"></span>
        </a>
        <a class ="delete-btn" href="<?= Url::to(['attachments/delete', 'id' => $item->id]); ?>", data-id="<?= $item->id; ?>">
            <span class="glyphicon glyphicon-trash"></span>
        </a>
    </div>
    <div style="clear:both;"></div>
    </li>
<?php } ?>
</ul>

<div id="dialog" style="display:none;">
    <label for="origname">Название</label>
    <input type="text" name="origname" id="origname" class="form-control"/>
    <input type="hidden" name="id" id="fileid"/>
</div>


<?php
$saveUrl = Url::to(['attachments/update']);
$priorityUrl = Url::to(['attachments/update-priority']);

$this->registerJs(
<<<JS
$("#sortable").sortable({
    stop: function(e, ui) {
        var arr = [];
        $("#sortable > li").each(function(i, el){
            arr.push($(el).attr("data-id"));
        });
        $.post("$priorityUrl", {ids: arr}, function(data){
            
        }, "json");
    }
});

$("#dialog").dialog({
    autoOpen: false,
    buttons: [
        {
            text: "Сохранить",
            click: function() {
                if ($("#origname").val().length > 0) {
                    var id = $("#fileid").val();
                    var origname = $("#origname").val();
                    $.post("$saveUrl", {id: id, origname: origname}, function(data){
                        if (data.success == true) {
                            $("#dialog").dialog("close");
                            loadAttachments();
                        }
                    }, "json");
                }
            },
        },
        {
            text: "Отмена",
            click: function() {
                $(this).dialog("close");
            }
        }
    ],
    close: function() {
        $("#origname").val("");
        $("#id").val("");
    }
});

$(".update-btn").click(function(){
    var url = $(this).attr("href");
    $.get(url, {}, function(data){
        if (data.success == true) {
            $("#origname").val(data.attachment.origname);
            $("#fileid").val(data.attachment.id);
            $("#dialog").dialog("open");
        }
    }, "json");
    return false;
});

$(".delete-btn").click(function(){
    if (confirm("Удалить?")) {
        var url = $(this).attr("href");
        var id = $(this).attr("data-id");
        $.post(url, {}, function(data){
            if (data.success == true) {
                $("#attachment-" + id).remove();
            }
        }, "json");
    }
    return false;
});
JS
);