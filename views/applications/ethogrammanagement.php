<?php
use yii\jui\Sortable;
$this->registerJsFile('@web/js/ethogram.js', ['position' => \yii\web\View::POS_END]);
$this->registerJs('$(".manage_blocks").ethogram({url: \'ethogramdata\'});');


yii\jui\Sortable::widget(['class' => 'manage_blocks']) 
?>

<div class="manage_blocks">

    
</div>

