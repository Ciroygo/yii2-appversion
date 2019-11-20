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
 * ChannelController 应用管理
 *
 * @category  PHP
 * @package   Yii2
 * @author    陈思辰 <chensichen@mocaapp.cn>
 * @copyright 2019 重庆次元能力科技有限公司
 * @license   https://www.moego.com/licence.txt Licence
 * @link      http://www.moego.com
 */
class ChannelController extends Controller
{
    /**
     * Version 管理首页
     */
    public function actionIndex()
    {
        $searchModel = new App();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $app = new App();
        return $this->render('index', [
        ]);
    }

    public function actionStore()
    {
        $app = new App();
        $params = Yii::$app->request->post();
        if ($app->load(Yii::$app->request->post(), null) && $app->validate()) {
            $app->save();
        } else {
            Yii::$app->getSession()->setFlash('error', "数据验证失败，请检查后重新输入");
        }
        // 不管成功与否，将信息存入 flash ，都走向白名单首页
        return $this->redirect('/appversion/app/index');
    }

    public function actionUpdate()
    {

    }

    public function actionDelete()
    {

    }
}
