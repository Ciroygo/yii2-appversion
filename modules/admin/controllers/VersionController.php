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

use Yii;
use yiiplus\appversion\modules\admin\models\ChannelVersion;
use yiiplus\appversion\modules\admin\models\Version;
use yiiplus\appversion\modules\admin\models\VersionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VersionController 版本管理
 *
 * @category  PHP
 * @package   Yii2
 * @author    陈思辰 <chensichen@mocaapp.cn>
 * @copyright 2019 重庆次元能力科技有限公司
 * @license   https://www.moego.com/licence.txt Licence
 * @link      http://www.moego.com
 */
class VersionController extends Controller
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
     * 版本管理首页
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new VersionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * 创建版本
     *
     * @param $app_id
     * @param $platform
     * @return string|\yii\web\Response
     */
    public function actionCreate($app_id = false, $platform = false)
    {
        $model = new Version();
        if ($app_id) {
            $model->app_id = $app_id;
        }
        if ($platform) {
            $model->platform = $platform;
        }

        if ($model->load(Yii::$app->request->post(), null) && $model->save()) {
            return $this->redirect(['index',  'VersionSearch[platform]' => $model->platform, 'VersionSearch[app_id]' => $model->app_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * 版本更新
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post(), null) && $model->save()) {
            return $this->redirect(['index',  'VersionSearch[platform]' => $model->platform, 'VersionSearch[app_id]' => $model->app_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * 废弃与启用
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionStatusToggle($id)
    {

        $model = $this->findModel($id);

        $model->status = ($model->status != 1) ? 1 : 2;

        $model->save();

        Yii::$app->getSession()->setFlash('success', '操作成功');
        return $this->redirect(['index',  'VersionSearch[platform]' => $model->platform, 'VersionSearch[app_id]' => $model->app_id]);
    }

    /**
     * 版本删除
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * 根据主键 id 查找模型，如果不存在则返回 404 错误
     *
     * @param $id
     * @return Version|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Version::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
