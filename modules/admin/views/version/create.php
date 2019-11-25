<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model yiiplus\appversion\modules\admin\models\Version */
/* @var $channelVersion yiiplus\appversion\modules\admin\models\ChannelVersion */

$this->title = '版本创建';
$this->params['breadcrumbs'][] = ['label' => 'Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="version-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'channelVersion' => $channelVersion
    ]) ?>

</div>
