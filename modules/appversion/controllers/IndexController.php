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

namespace yiiplus\appversion\modules\appversion\controllers;

use app\extensions\ApiException;
use yii\base\DynamicModel;
use yiiplus\appversion\modules\admin\models\ChannelVersion;
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
     * 行为控制
     *
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authValidate'] = [
            'class' => 'app\extensions\auth\AccessTokenAuth',
            'optional' => ['*'],
        ];

        return $behaviors;
    }

    /**
     * 版本接口最新信息获取
     *
     * @return array
     * @throws ApiException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $model = DynamicModel::validateData(Yii::$app->request->post(), [
            [['app_id', 'platform', 'name', 'channel'], 'required'],
            [['app_id', 'platform', 'channel'], 'integer'],
            ['name', 'match', 'pattern'=>'/^[1-9]\d*\.[0-9]\d*\.[0-9]\d*$/', 'message'=>'格式形如为 999.999.999'],
        ]);

        if ($model->hasErrors()) {
            // 验证失败
            throw new ApiException(ApiException::SYSTEM_PARAM_ERROR);
        }

        // 根据版本号，获取当前最新的版本
        $version = (new ChannelVersion)->getLatest($model);
        return $version;
    }
}
