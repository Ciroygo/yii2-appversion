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
use yii\web\Controller;

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
     * app管理首页
     */
    public function actionIndex()
    {
        $searchModel = new App();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $app = new App();
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'app' => $app
        ]);
    }

    /**
     * 添加应用
     *
     * @return \yii\web\Response
     */
    public function actionStore()
    {
        $app = new App();
        if ($app->load(Yii::$app->request->post(), null) && $app->validate()) {
            $app->operated_id = Yii::$app->user->id;
            $app->save();
        } else {
            Yii::$app->getSession()->setFlash('error', "数据验证失败，请检查后重新输入");
        }
        //不管成功与否，将信息存入 flash ，都走向白名单首页
        return $this->redirect('/appversion/app/index');
    }

    /**
     * 查看应用
     *
     * @param $id
     * @return string|\yii\web\Response
     */
    public function actionView($id)
    {
        $app = App::findOne($id);
        if (!$app) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        return $this->render('view', [
            'app' => $app
        ]);
    }

    public function actionUpdate($id)
    {
        $app = App::findOne($id);
        if (!$app) {
            return $this->redirect(Yii::$app->request->referrer);
        }
        return $this->render('update', [
            'app' => $app
        ]);
    }

    /**
     * 删除应用
     *
     * @param $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $app = App::findOne($id)->delete();
        if ($app) {
            Yii::$app->getSession()->setFlash('success', "删除成功");
        } else {
            Yii::$app->getSession()->setFlash('error', "删除失败，请重试");
        }
        return $this->redirect(Yii::$app->request->referrer);
    }
}
