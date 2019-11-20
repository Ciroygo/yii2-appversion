<?php

use yii\db\Migration;

/**
 * 添加渠道表
 */
class m191120_103045_create_yp_appversion_channel_table extends Migration
{
    /**
     * 执行迁移
     */
    public function safeUp()
    {
        $this->createTable('{{%yp_appversion_channel}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * 回滚迁移
     */
    public function safeDown()
    {
        $this->dropTable('{{%yp_appversion_channel}}');
    }
}
