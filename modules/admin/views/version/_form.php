<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiiplus\appversion\modules\admin\models\App;
use yiiplus\appversion\modules\admin\models\Version;
use yiiplus\appversion\modules\admin\models\Channel;

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

    <?php
        $channels = Channel::find()->select(['id', 'name'])->asArray()->all();
    ?>


    <?php foreach ($channelVersions as $key => $channelVersion) { ?>

        <?= $form->field($channelVersions[$key], "channel_id")->label('渠道 '. $channelVersion->channel->name)
            ->dropdownList(array_combine(array_column($channels,'id'), array_column($channels,'name')), ['prompt'=>'选择渠道']); ?>
        <?= $form->field($channelVersions[$key], 'url')->textInput() ?>

    <?php
        }
    ?>



    <?= $form->field($model, 'scope')->dropdownList(Version::SCOPE_TYPE) ?>

    <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
