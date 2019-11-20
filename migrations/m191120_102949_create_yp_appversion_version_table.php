<?php

use yii\db\Migration;

/**
 * 添加版本表
 */
class m191120_102949_create_yp_appversion_version_table extends Migration
{
    /**
     * 执行迁移
     */
    public function safeUp()
    {
        $this->createTable('{{%yp_appversion_version}}', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * 回滚迁移
     */
    public function safeDown()
    {
        $this->dropTable('{{%yp_appversion_version}}');
    }
}
