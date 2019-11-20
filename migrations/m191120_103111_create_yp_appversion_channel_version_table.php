<?php

use yii\db\Migration;

/**
 * 添加版本渠道中间关联表
 */
class m191120_103111_create_yp_appversion_channel_version_table extends Migration
{
    /**
     * 执行迁移
     */
    public function safeUp()
    {
        $this->createTable('{{%yp_appversion_channel_version}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * 回滚迁移
     */
    public function safeDown()
    {
        $this->dropTable('{{%yp_appversion_channel_version}}');
    }
}
