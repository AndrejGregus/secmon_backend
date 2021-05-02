<?php

use macgyer\yii2materializecss\widgets\grid\GridView;
use yii\widgets\Pjax;
use kartik\cmenu\ContextMenu;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EventsClusteredSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['title'] = 'Clustered Events Run ID: ' . $_GET['run_id'];

$this->registerJs('
    setInterval(function() {
        $.pjax.reload({
            container:"#pjaxContainer table#eventsContent tbody:last", 
            fragment:"table#eventsContent tbody:last"})
            .done(function() {
                activateEventsRows();
                $.pjax.reload({
                    container:"#pjaxContainer #pagination", 
                    fragment:"#pagination"
                });
            });
    }, 5000);
');

?>
<div class="events-normalized-index clickable-table">
    <?php Pjax::begin(['id' => 'pjaxContainer']); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'layout' => '{items}<div id="pagination">{pager}</div>',
                'tableOptions' => [
                    'id' => 'eventsContent',
                    'class' => 'responsive-table striped'
                ],
                'columns' => [
                    [
                            'class' => 'yii\grid\SerialColumn',
                    ],
                        
                        'id',
                        [
                            'attribute' => 'severity',
                            'value' => 'severity',
                            'contentOptions' => function ($dataProvider, $key, $index, $column) {
                                $array = array("#00DBFF", "#00FF00", "#FFFF00", "#CC5500", "#FF0000");
                                $cluster_severity = $dataProvider->severity;

                                if (0 < $cluster_severity && $cluster_severity < 11){
                                    return ['style' => 'background-color:'.$array[($cluster_severity - 1) / 2]];
                                } else {
                                    return ['style' => 'background-color:#FFFFFF'];
                                }
                            }
                        ],
                        'number_of_events',
                        'comment',
                        ['class' => 'macgyer\yii2materializecss\widgets\grid\ActionColumn', 'template'=>'{update}{view}'],
                    ],
            ]); ?>
    <?php Pjax::end(); ?>
