<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;
use app\models\Attachment;
use app\models\Document;

use app\models\UploadForm;

/**
 * Description of AttachmentsController
 *
 *  @author Ilya Podgursky <2377635@gmail.com>
 */
class AttachmentsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'update' => ['post'],
                    'delete' => ['post'],
                    'upload' => ['post'],
                    'update-priority' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionIndex($documentID)
    {
        $document = $this->loadDocument($documentID);
        
        return $this->renderAjax('index', [
            'attachments' => $document->attachments,
        ]);
    }
    
    public function actionUpload($documentID = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $result = ['success' => false];
        
        $uploadForm = new UploadForm();
        
        $uploadForm->file = UploadedFile::getInstanceByName('file');
        $uploadForm->documentID = $documentID;
        
        if ($uploadForm->validate() && $uploadForm->upload()) {
            $result['success'] = true;
        }
        
        return $result;
    }
    
    public function actionView($id)
    {
        $attachment = $this->loadAttachment($id);
        
        return Yii::$app->response->sendFile($attachment->getFullPath(), $attachment->origname, ['inline' => true]);
    }
    
    public function actionGet($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $result = ['success' => false];
        
        $attachment = $this->loadAttachment($id);
        
        $result['success'] = true;
        $result['attachment'] = $attachment;
        
        return $result;
    }
    
    public function actionUpdate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $result = ['success' => false];
        
        $id = Yii::$app->request->post('id');
        
        $attachment = $this->loadAttachment($id);
        
        $attachment->origname = htmlspecialchars(Yii::$app->request->post('origname'));
        
        if ($attachment->save()) {
            $result['success'] = true;
        }
        
        return $result;
    }
    
    public function actionDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $result = ['success' => false];
        
        $attachment = $this->loadAttachment($id);
        
        if ($attachment->delete()) {
            $result['success'] = true;
        }
        
        return $result;
    }
    
    public function actionUpdatePriority()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $ids = Yii::$app->request->post('ids');
        
        if (is_array($ids)) {
            Attachment::updatePriority($ids);
        }
            
        return ['success' => true];
    }
    
    public function loadAttachment($id)
    {
        if (($document = Attachment::findOne($id)) !== null) {
            return $document;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Загрузка документа
     * @param integer $id
     * @return app\models\Document
     * @throws NotFoundHttpException
     */
    public function loadDocument($id)
    {
        if (($document = Document::findOne($id)) !== null) {
            return $document;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
