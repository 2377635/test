<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

use app\models\Attachment;

/**
 * Description of Document
 *
 * @author Ilya Podgursky <2377635@gmail.com>
 * 
 * @property integer $id
 * @property string $title
 * @property string $description
 * 
 * @property Attachment[] $attachments
 */
class Document extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documents';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['status'], 'default', 'value' => self::STATUS_INACTIVE],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'description' => 'Описание',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachments()
    {
        return $this->hasMany(Attachment::className(), ['document_id' => 'id']);
    }
    
    /**
     * Поиск всех документов
     * @return yii\data\ArrayDataProvider
     */
    public static function search()
    {
        return new ActiveDataProvider([
            'query' => self::find()->where(['status' => self::STATUS_ACTIVE]),
        ]);
    }
    
    /**
     * Удаляет неактивные (несохраненные) документы
     */
    public static function deleteInactive()
    {
        self::deleteAll(['status' => self::STATUS_INACTIVE]);
    }
    
    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            // при удалении документа, удаляем все файлы к нему
            Attachment::deleteAll(['document_id' => $this->id]);
            return true;
        } else {
            return false;
        }
    }
}
