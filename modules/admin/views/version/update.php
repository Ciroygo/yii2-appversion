<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yiiplus\appversion\modules\admin\models\Version */
/* @var $channelVersions yiiplus\appversion\modules\admin\models\ChannelVersion */
/* @var $channelVersion yiiplus\appversion\modules\admin\models\ChannelVersion */

$this->title = 'Update Version: ' . $model->app->name . " 版本名：" . $model->name  ;
$this->params['breadcrumbs'][] = ['label' => '应用列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->app->name . '版本列表', 'url' => ['index', "VersionSearch[app_id]" => $model->app_id, "VersionSearch[platform]" => $model->platform]];
$this->params['breadcrumbs'][] = '编辑';
?>
<div class="version-update">
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'channelVersion' => $channelVersion
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
