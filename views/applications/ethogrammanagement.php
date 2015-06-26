<?php
        $this->registerJsFile('@web/js/ethogram.js');
?>

<div class="manage_animals">
    <div>
        <div class="m_title_wrapper">
            <div class="m_title">Aggressive</div>
            <div class="m_title_edit">
                <a class="collapseL" href="#"></a>
                <a class="delete" href="#"></a>
                <a class="edit" href="#"></a>
            </div>
        </div>
        <div class="m_slide">
            <div class="m_text"><span>Touch</span><a class="delete" href="#"></a><a class="edit" href="#"></a></div>
            <div class="m_text"><span>Touch</span><a class="delete" href="#"></a><a class="edit" href="#"></a></div>
            <div id="m_action">Add new action</div>
        </div>
    </div>
    
    <hr class="sep"/>

    <div class="m_type_text"><input placeholder="Type a name of behavior" type="text"></div>
    <div class="m_buttons"><button class="m_add_action">Add new action</button></div>
</div>
<script>$(ehtogram)</script>
