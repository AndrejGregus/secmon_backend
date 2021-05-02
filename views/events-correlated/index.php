<?php

use macgyer\yii2materializecss\widgets\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EventsCorrelatedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['title'] = 'Correlated Events';

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
<div class="events-correlated-index clickable-table">
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
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'datetime',
                    'value' => 'datetime',
                    'format' => 'raw',
                    'filter' => \macgyer\yii2materializecss\widgets\form\DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'datetime',
                        'clientOptions' => [
                            'format' => 'yyyy-mm-dd'
                        ]
                    ])
                ],
                'host',
                'cef_name',
                [
                        'attribute' => 'cef_severity',
                        'value' => 'cef_severity',
                        'contentOptions' => function ($dataProvider, $key, $index, $column) {
                            $array = array("#00DBFF", "#00FF00", "#FFFF00", "#CC5500", "#FF0000");
                            $event_severity = $dataProvider->cef_severity;

                            if (0 < $event_severity && $event_severity < 11){
                                return ['style' => 'background-color:'.$array[($event_severity - 1) / 2]];
                            } else {
                                return ['style' => 'background-color:#FFFFFF'];
                            }
                        }
                    ],
                ['class' => 'macgyer\yii2materializecss\widgets\grid\ActionColumn', 'template'=>'{view}'],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>
