<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yiiplus\appversion\modules\admin\models\Version */
/* @var $channelVersions yiiplus\appversion\modules\admin\models\ChannelVersion */

$this->title = 'Update Version: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="version-update">

    <h1><?= Html::encode($this->title) ?></h1>


    <div class="row">
        <div class="col-sm-6">
            <div class="box">
                <div class="box-body">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'channelVersions' => $channelVersions
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">添加渠道</h3>
                </div>
                <div class="box-body">

                    <?= $this->render('/channel/_form', [
                        'model' => new \yiiplus\appversion\modules\admin\models\Channel(),
                    ]) ?>
                </div>
            </div>

        </div>
    </div>


</div>
