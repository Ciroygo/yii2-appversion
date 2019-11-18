<?php

use yii\db\Migration;

/**
 * 添加 app 管理表
 */
class m191116_064031_create_yp_appversion_app extends Migration
{
    /**
     * 执行迁移
     */
    public function safeUp()
    {
        $this->createTable('{{%yp_appversion_app}}', [
            'id' => "int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id'",
            'name' => "varchar(64) COMMENT '应用名称'",
            'application_id' => "varchar(255) COMMENT '应用名称'",
            'operated_id' => "int(11) COMMENT '用户id'",
            'is_del' => "tinyint(1) DEFAULT '0' COMMENT '状态；0正常；1主动删除；2后台删除'",
            'created_at' => "int(11) DEFAULT NULL COMMENT '创建时间'",
            'updated_at' => "int(11) DEFAULT NULL COMMENT '更新时间'",
            "deleted_at" => "int(11) DEFAULT NULL COMMENT '删除时间'",
            "PRIMARY KEY(`id`)"
        ], "ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COMMENT = '反作弊用户表'");

        $this->createIndex('unique_index_uid', 'yp_appversion_app', 'operated_id', true);
    }

    /**
     * 回滚迁移
     */
    public function safeDown()
    {
        $this->dropTable('{{%yp_appversion_app}}');
    }
}
