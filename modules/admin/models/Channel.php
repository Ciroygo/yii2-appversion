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

use common\models\system\AdminUser;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Channel 渠道表
 *
 * @category  PHP
 * @package   Yii2
 * @author    陈思辰 <chensichen@mocaapp.cn>
 * @copyright 2019 重庆次元能力科技有限公司
 * @license   https://www.moego.com/licence.txt Licence
 * @link      http://www.moego.com
 *
 * This is the model class for table "yp_appversion_channel".
 *
 * @property int $id 主键id
 * @property string $name 应用名称
 * @property string $application_id 应用名称
 * @property int $operated_id 用户id
 * @property int $is_del 状态；0正常；1主动删除；2后台删除
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $deleted_at 删除时间
 */
class Channel extends ActiveRecord
{
    public static function tableName()
    {
        return 'yp_appversion_channel';
    }

    public function getChannelVersions()
    {
        return $this->hasMany(ChannelVersion::className(), ['channel_id' => 'id']);
    }

    public function getVersions()
    {
        return $this->hasMany(Version::className(), ['id' => 'version_id'])
            ->via('channelVersions');
    }
}
