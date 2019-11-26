<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiiplus\appversion\modules\admin\models\App;
use yiiplus\appversion\modules\admin\models\Version;
use yiiplus\appversion\modules\admin\models\Channel;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model yiiplus\appversion\modules\admin\models\Version */
/* @var $form yii\widgets\ActiveForm */
/* @var $channelVersions yii\widgets\ActiveForm */
?>

<div class="version-form">

    <?php $form = ActiveForm::begin();?>

    <?php
        $apps = App::find()->select(['id', 'name'])->asArray()->all();
    ?>
    <?= $form->field($model, 'app_id')->dropdownList(array_combine(array_column($apps,'id'), array_column($apps,'name')), ['prompt'=>'选择应用']); ?>

    <?= $form->field($model, 'code')->textInput() ?>

    <?= $form->field($model, 'min_code')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'min_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(Version::UPDATE_TYPE, ['prompt'=>'选择更新类型']) ?>

    <?= $form->field($model, 'platform')->dropdownList(App::PLATFORM_OPTIONS, ['prompt'=>'选择平台']) ?>

    <?= $form->field($model->channels, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model->channels, 'name')->textInput(['maxlength' => true]) ?>

    <?php
        $channels = Channel::find()->select(['id', 'name'])->where(['platform' => $model->platform])->asArray()->all();
    ?>

    <?php //Pjax::begin(); ?>
    <div class="text-bold margin-bottom">渠道添加: </div>
    <?php foreach ($channelVersions as $key => $channelVersion) { ?>

        <div class="panel panel-info">
            <div class="panel-heading">
                <a href="" class="close" >
                    <span aria-hidden="true">&times;</span>
                </a>
                <h3 class="panel-title"><?php echo $channelVersion->channel->name ?></h3>
            </div>
            <div class="panel-body">
                <?php //$form->field($channelVersions[$key], "[$key]channel_id")->label('渠道选择 '.' <a href="">移除</a>')
                    //->dropdownList(Channel::getChannelOptions($model->platform), ['prompt'=>'选择渠道']); ?>
                <?= $form->field($channelVersions[$key], "[$key]url")->textInput() ?>
            </div>
        </div>
    <?php
        }
    ?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">新增渠道</h3>
        </div>
        <div class="panel-body">
            <?= Html::button('添加', ['class' => 'btn btn-success', "data-toggle"=>"modal", "data-target"=>"#add-channel"]) ?>
        </div>
    </div>


    
    <?php //Pjax::end(); ?>


    <?= $form->field($model, 'scope')->dropdownList(Version::SCOPE_TYPE) ?>

    <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<!-- 添加渠道 -->
<div class="modal fade" id="add-channel" tabindex="-1" role="dialog" aria-labelledby="add-channel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">添加渠道</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php $form = ActiveForm::begin([
                'action' => ['add-channel'],
                'method' => 'post',
            ]); ?>
            <div class="modal-body">
                <div class="form-group">
                    <?= $form->field($channelVersions[0], "channel_id")
                        ->dropdownList(Channel::getChannelOptions($model->platform), ['prompt'=>'选择渠道']); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <?= Html::submitButton('添加渠道', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

