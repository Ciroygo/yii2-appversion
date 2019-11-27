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

namespace yiiplus\appversion\modules\version\controllers;

use yii\base\DynamicModel;
use yiiplus\appversion\modules\admin\models\App;
use yiiplus\appversion\modules\admin\models\ChannelVersion;
use yiiplus\appversion\modules\admin\models\Version;
use app\modules\Controller;
use Yii;

/**
 * IndexController 版本获取接口
 *
 * @category  PHP
 * @package   Yii2
 * @author    陈思辰 <chensichen@mocaapp.cn>
 * @copyright 2019 重庆次元能力科技有限公司
 * @license   https://www.moego.com/licence.txt Licence
 * @link      http://www.moego.com
 */
class IndexController extends Controller
{

    /**
     * 版本接口最新信息获取
     *
     * @return array
     */
    public function actionIndex()
    {

        $model = DynamicModel::validateData(Yii::$app->request->post(), [
            [['app_id', 'platform', 'code', 'name', 'channel'], 'required'],
            [['app_id', 'platform', 'code', 'channel'], 'integer'],
            ['name', 'match', 'pattern'=>'/^[1-9]\d*\.[0-9]\d*\.[0-9]\d*$/', 'message'=>'格式形如为 1.1.1'],
        ]);

        if ($model->hasErrors()) {
            // 验证失败
        }

        $version = (new ChannelVersion)->latest($model);
        return $version;
    }
}
