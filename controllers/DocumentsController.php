<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;

use app\models\Document;

/**
 * Description of DocumentsController
 *
 *  @author Ilya Podgursky <2377635@gmail.com>
 */
class DocumentsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        return $this->render('index', [
            'dataProvider' => Document::search(),
        ]);
    }
    
    public function actionCreate()
    {
        Document::deleteInactive();
        
        $document = new Document();
        
        $document->save();
        
        return $this->redirect(['update', 'id' => $document->id]);
    }
    
    public function actionUpdate($id)
    {
        $document = $this->loadDocument($id);
        
        $document->status = Document::STATUS_ACTIVE;
        
        if ($document->load(Yii::$app->request->post()) && $document->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('form', [
                'document' => $document,
            ]);
        }
    }
    
    public function actionView($id)
    {
        $document = $this->loadDocument($id);
        
        return $this->render('view', [
            'document' => $document,
            'attachments' => $document->attachments,
        ]);
    }
    
    public function actionDelete($id)
    {
        $document = $this->loadDocument($id);
        $document->delete();
        return $this->redirect(['index']);
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
