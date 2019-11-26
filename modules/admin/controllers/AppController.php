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
use yiiplus\appversion\modules\admin\models\App;
use yiiplus\appversion\modules\admin\models\AppSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AppController 应用管理
 *
 * @category  PHP
 * @package   Yii2
 * @author    陈思辰 <chensichen@mocaapp.cn>
 * @copyright 2019 重庆次元能力科技有限公司
 * @license   https://www.moego.com/licence.txt Licence
 * @link      http://www.moego.com
 */
class AppController extends Controller
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
     *  app 管理首页
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AppSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * app 创建
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new App();
        if ($model->load(Yii::$app->request->post(), null) && ($model->operated_id = Yii::$app->user->id ?? 0) &&$model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * app 应用修改
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post(), null) && ($model->operated_id = Yii::$app->user->id ?? 0) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * 应用删除
     *
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $app = $this->findModel($id);

        $this->findModel($id)->delete();
        // todo 删除版本信息、渠道关联
//        $app->unlinkAll('versions', true);
//        $app->unlinkAll('versions', true);

        return $this->redirect(['index']);
    }

    /**
     * 根据主键 id 查找模型，如果不存在则返回 404 错误
     *
     * @param $id
     * @return App|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = App::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
