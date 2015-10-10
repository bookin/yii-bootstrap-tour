This is a Bootstrap Tour extension for Yii 1.1.*

It encapsulates the Bootstrap Tour component in terms of a Yii widget, and thus makes using Bootstrap Tour component in Yii applications extremely easy.

###Usage###

```
<?php
$this->widget('ext.bootstrap-tour.Tour', [
    'name'=>'name-for-tour',
    'options'=>[
        'onEnd'=>'js:function(tour){
            window.location.href="/activity";
        }',
        'steps'=>[
            [
                'element' => '#categories-select',
                'title'=>'Step 1',
                'content'=>'Description for step 1',
                'placement'=>'bottom'
            ],
            [
                'element'=> '.bookmark:first',
                'title'=>'Step 2',
                'content'=>'Description for step 2',
                'placement'=>'top'
            ],
            ...
        ],
        'storage'=>false,
    ],
]);
?>
```