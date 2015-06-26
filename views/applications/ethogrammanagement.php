<?php
$this->registerJsFile('@web/js/ethogram.js', ['position' => \yii\web\View::POS_END]);
$this->registerJs('$(".manage_blocks").ethogram();');
?>

<div class="manage_blocks">
    <div class="ma_block">
        <div class="m_title_wrapper">
            <div class="m_title">Aggressive</div>
            <div class="m_title_edit">
                <a class="collapseL" href="#"></a>
                <a class="delete" href="#"></a>
                <a class="edit" href="#"></a>
            </div>
        </div>
        <div class="m_slide">
            <div class="m_text"><input type="text" placeholder="Touch" class="newInput"><a class="delete" href="#"></a><a class="edit" href="#"></a></div>
            <div class="m_text"><input type="text" placeholder="Touch" class="newInput"><a class="delete" href="#"></a><a class="edit" href="#"></a></div>
            <div class="m_action">Add new action</div>
        </div>
    </div>
    <div class="ma_block inactive">
        <div class="m_slide">  
            <div class="m_text"><input type="text" placeholder="Type a name of behavior" class="newInput"><a class="delete" href="#"></a><a class="edit" href="#"></a></div>
            <div class="m_buttons"><div class="m_action">Add new action</div></div>
        </div>
    </div>
</div>

