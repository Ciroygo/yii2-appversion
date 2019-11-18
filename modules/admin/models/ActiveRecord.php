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

namespace yiiplus\appversion\modules\admin\models;

use yii\behaviors\TimestampBehavior;

/**
 * ActiveRecord 模型基类
 *
 * @category  PHP
 * @package   Yii2
 * @author    陈思辰 <chensichen@mocaapp.cn>
 * @copyright 2019 重庆次元能力科技有限公司
 * @license   https://www.moego.com/licence.txt Licence
 * @link      http://www.moego.com
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * NOT_DELETED是删除状态，它表示这条数据未被删除
     */
    const NOT_DELETED = 0;

    /**
     * ACTIVE_DELETE是删除状态，它表示这条数据被主动删除
     */
    const ACTIVE_DELETE = 1;

    /**
     * BACKGROUND_DELETE是删除状态，它表示这台数据被后台删除
     */
    const BACKGROUND_DELETE = 2;

    /**
     * FONT_END_PAGESIZE是前端常用每页数目
     */
    const FONT_END_PAGESIZE = 21;

    /**
     * PAGENUM是页数
     */
    const PAGE_NUM = 0;

    /**
     * APP IOS版本
     */
    const MOEGO_IOS_VERSION = 26;

    /**
     * APP IOS版本
     */
    const MOEGO_ANDROID_VERSION = 21;

    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array the behavior configurations.
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * Populates the model with input data.
     *
     * @param array $data the data array to load, typically `$_POST` or `$_GET`.
     * @param string $formName the form name to use to load the data into the model.
     * If not set, [[formName()]] is used.
     *
     * @return bool whether `load()` found the expected form in `$data`.
     */
    public function load($data, $formName = '')
    {
        return parent::load($data, $formName);
    }

    /**
     * 验证模型类是否存在
     *
     * @param array  $params     参数
     * @param bool   $exception  是否抛出异常
     * @param string $errorModel 错误类
     * @param string $error      错误信息
     *
     * @return static
     */
    public static function validateModel($params, $exception = false, $errorModel = \Exception::class, $error = '参数错误')
    {
        $model = static::find()->where($params)->one();
        if (!$model) {
            if ($exception) {
                throw new $errorModel($error);
            }
        } else {
            return $model;
        }
    }
}
