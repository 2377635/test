<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;

use app\models\Attachment;

/**
 * Description of UploadForm
 *
 * @author Ilya Podgursky <2377635@gmail.com>
 */ 
 
class UploadForm extends Model
{
    /**
     * @var \yii\web\UploadedFile
     */
    public $file;
    
    /**
     * @var integer
     */
    public $documentID;
    
    /**
     * 
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documentID'], 'integer'],
            [['file'], 'file']
        ];
    }
    
    /**
     * Загрузка файла. При успехе создание и сохранение модели Attachment
     * @return boolean
     */
    public function upload()
    {
        $ext = $this->file->extension;
        $origName = $this->file->baseName.'.'.$ext;
        $size = $this->file->size;
        $fileName = md5(uniqid()).'.'.$ext;
        $path = $this->getUploadDir().'/'.$fileName;
        
        if ($this->file->saveAs($path)) {
            // создаем и сохраняем модель Attachment
            $attachment = new Attachment();
            $attachment->document_id = $this->documentID;
            $attachment->origname = $origName;
            $attachment->filename = $fileName;
            $attachment->mimetype = FileHelper::getMimeType($path);
            $attachment->size = $size;
            
            if ($attachment->save()) {
                return true;
            } else {
                // если ничего не получилось, удаляем загруженный файл
                unlink($path);
            }
        }
        
        return false;
    }
    
    /**
     * Возвращает (при необходимости создает) директорию для загрузки файлов
     * @return string
     */
    public function getUploadDir()
    {
        $dir = Yii::getAlias('@webroot/files/').$this->documentID;
        
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        
        return $dir;
    }
}
