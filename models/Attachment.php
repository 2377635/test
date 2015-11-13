<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Description of Attachment
 *
 * @author Ilya Podgursky <2377635@gmail.com>
 * 
 * @property integer $id
 * @property integer $document_id
 * @property string $origname
 * @property string $filename
 * @property string $mimetype
 * @property integer $priority
 */

class Attachment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attachments';
    }
    
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                // устанавливаем порядок сортировки для вновь созданных элементов
                $maxPriority = self::find()->
                        where(['document_id' => $this->document_id])->
                        orderBy('priority desc')->
                        max('priority');

                $this->priority = $maxPriority !== null ? $maxPriority + 1 : 0;
            }
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            // при удалении записи, удаляем файл на диске
            @unlink($this->getFullPath());
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Полный путь к файлу
     * @return string
     */
    public function getFullPath()
    {
        return Yii::getAlias('@webroot/files/').$this->document_id.'/'.$this->filename;
    }
    
    /**
     * Обновляет порядок файлов
     * @param array $ids
     */
    public static function updatePriority($ids)
    {
        foreach ($ids as $priority => $id) {
            $attachment = self::findOne($id);
            $attachment->priority = $priority;
            $attachment->save();
        }
    }
}
