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

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel yiiplus\appversion\modules\admin\models\AppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Apps 管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
if (!empty(Yii::$app->session->getFlash('success'))) {
    echo Alert::widget([
        'options' => ['class' => 'alert-info'],
        'body' => Yii::$app->session->getFlash('success'),
    ]);
} elseif (!empty(Yii::$app->session->getFlash('error'))) {
    echo Alert::widget([
        'options' => ['class' => 'alert-error'],
        'body' => Yii::$app->session->getFlash('error'),
    ]);
}
?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><?= $this->title ?></h3>
        <div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
            <?= Html::a('<i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;新增',
                ['create'], ['class' => 'btn btn-sm btn-success']) ?>
        </div>
    </div>

    <div class="box-body">
        <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
            <div class="row"><div class="col-sm-6"></div>
                <div class="col-sm-6"></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'header' => '操作id',
                                'attribute' => 'id',
                                'filter' => true,
                                'value' => function ($data) {
                                    return $data->id;
                                }
                            ],
                            'name',
                            'application_id',
                            [
                                'attribute'=>'operator',
                                'value' => function ($model) {
                                    return $model->operator->username ?? null;
                                }
                            ],
                            [
                                'attribute'=>'created_at',
                                'value' => function ($model) {
                                    return date("Y-m-d H:i:s", $model->created_at);
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => '操作',
                            ],
                        ],
                        'showFooter' => false,
                        'layout'=>"{items}<div class='col-sm-11'>{summary}<div class='pull-right'>{pager}</div></div>",
                        'tableOptions' => ['class' => 'table table-hover']
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
