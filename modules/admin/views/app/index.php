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
use yii\bootstrap\Alert;
use admin\modules\member\models\UserBan;

$this->title = 'App管理';
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
        <h3 class="box-title">App 列表</h3>
        <div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
            <a href="/appversion/app/index" class="btn btn-sm btn-success" title="新增">
                <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;新增</span>
            </a>
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
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'header' => '操作id',
                                'attribute' => 'id',
                                'filter' => true, //不显示搜索框
                                'value' => function ($data) {
                                    return $data->id;
                                }
                            ],
                            'name',
                            'application_id',
                            'operated_id',
                            'is_del',
                            [
                                'attribute'=>'created_at',
                                'value' => function ($model) {
                                    return date("Y-m-d H:i:s", $model->created_at);
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => '操作',
                                'headerOptions' => ['width' => '10'],
                                'template' => '{view}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::a('用户详情', ['view', 'id' => $key], ['class' => 'btn btn-success',]);
                                    }
                                ],
                            ],
                        ],
                        'layout'=>"{items}\n{summary}{pager}",
                        'tableOptions' => ['class' => 'table table-hover']
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
