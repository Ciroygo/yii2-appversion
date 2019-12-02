<?php
/**
 * 萌股 - 二次元潮流聚集地
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    陈思辰 <chensichen@mocaapp.cn>
 * @copyright 2019 重庆次元能力科技有限公司
 * @license   https://www.moego.com/licence.txt Licence
 * @link      http://www.moego.com
 */

namespace yiiplus\appversion\modules\admin\controllers;

use yii\web\UploadedFile;
use yiiplus\appversion\modules\admin\models\ActiveRecord;
use yiiplus\appversion\modules\admin\models\App;
use yiiplus\appversion\modules\admin\models\Version;
use Yii;
use yiiplus\appversion\modules\admin\models\ChannelVersion;
use yiiplus\appversion\modules\admin\models\ChannelVersionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ChannelVersionController 渠道关联表
 *
 * @category  PHP
 * @package   Yii2
 * @author    陈思辰 <chensichen@mocaapp.cn>
 * @copyright 2019 重庆次元能力科技有限公司
 * @license   https://www.moego.com/licence.txt Licence
 * @link      http://www.moego.com
 */
class ChannelVersionController extends Controller
{
    /**
     * 过滤器
     *
     * @return array
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
     * 首页
     *
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
     * 创建页
     *
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
        if ($model->load(Yii::$app->request->post(), null)) {
            if ($model->version->platform == App::ANDROID) {
                $file = UploadedFile::getInstances($model, 'url');

                if (empty($file)) {
                    Yii::$app->getSession()->setFlash('error', '没有渠道包，上传失败');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                $path = Yii::$app->cos->cos_url . Yii::$app->storage->save($file[0], ChannelVersion::UPLOAD_APK_DIR);
                if ($path) {
                    $model->url = $path;
                } else {
                    Yii::$app->getSession()->setFlash('error', '渠道包上传失败');
                    return $this->redirect(['index',  'version_id' => $version->id]);
                }
            }
            $model->save();
            return $this->redirect(['index',  'version_id' => $version->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'version' => $version
        ]);
    }

    /**
     * 更新
     *
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
            if ($model->version->platform == App::ANDROID && ($file = UploadedFile::getInstances($model, 'url'))) {
                $path = Yii::$app->cos->cos_url . Yii::$app->storage->save($file[0], ChannelVersion::UPLOAD_APK_DIR);

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
     * 删除
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ($model = $this->findModel($id)) {
            $model->is_del = ActiveRecord::ACTIVE_DELETE;
            $model->save();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * 模型查找
     *
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
