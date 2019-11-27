<?php

namespace yiiplus\appversion\modules\admin\controllers;

use common\extensions\uploadFile\UploadFile;
use yii\web\UploadedFile;
use yiiplus\appversion\modules\admin\models\App;
use yiiplus\appversion\modules\admin\models\Version;
use Yii;
use yiiplus\appversion\modules\admin\models\ChannelVersion;
use yiiplus\appversion\modules\admin\models\ChannelVersionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ChannelVersionController implements the CRUD actions for ChannelVersion model.
 */
class ChannelVersionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ChannelVersion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ChannelVersionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new ChannelVersion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($version_id)
    {
        $version = Version::findOne($version_id);
        if (!$version) {
            Yii::$app->getSession()->setFlash('error', '不存在的版本号');
            return $this->redirect(['/appversion/app']);
        }
        $model = new ChannelVersion();
        $model->version_id = $version->id;
        if ($model->load(Yii::$app->request->post(), null) && $model->save()) {

            if ($model->version->platform == App::ANDROID) {
                $file = UploadedFile::getInstances($model, 'url');

                $path = Yii::$app->cos->cos_url . Yii::$app->storage->save($file[0], 'version/apk');

                $model->url = $path;

                $model->save();
            }

            return $this->redirect(['index',  'version_id' => $version->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'version' => $version
        ]);
    }

    /**
     * Updates an existing ChannelVersion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param integer $version_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $version_id)
    {
        $version = Version::findOne($version_id);
        if (!$version) {
            Yii::$app->getSession()->setFlash('error', '不存在的版本号');
            return $this->redirect(['/appversion/app']);
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->version->platform == App::ANDROID) {
                $file = UploadedFile::getInstances($model, 'url');

                $path = Yii::$app->cos->cos_url . Yii::$app->storage->save($file[0], 'version/apk');

                $model->url = $path;

                $model->save();
            }
            return $this->redirect(['index',  'version_id' => $version->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'version' => $version
        ]);
    }

    /**
     * Deletes an existing ChannelVersion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ChannelVersion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ChannelVersion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ChannelVersion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
