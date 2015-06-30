/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */;

jQuery(document).ready(function() {
    $(".manage_blocks").sortable({
        items: '.ma_block:not(.inactive)',
        stop: function(event, ui) {
            console.log("New position: " + ui.item.index());
        }
    });
    $(".manage_blocks").disableSelection();
});

(function($) {
    var title = "<div class=\"m_title_wrapper\"><div class=\"m_title\"></div><div class=\"m_title_edit\"><a class=\"collapseL\" href=\"#\"></a><a class=\"delete\" href=\"#\"></a><a class=\"edit\" href=\"#\"></a></div></div>";
    var field = "<div class=\"m_text\"><input type=\"text\" placeholder=\"Touch\" class=\"newInput\"><a class=\"delete\" href=\"#\"></a><a class=\"edit\" href=\"#\"></a></div>";
    var button = "<div class=\"m_action\">Add new action</div>";
    var inactive = "<div class=\"ma_block inactive\"><div class=\"m_slide\"><div class=\"m_text\"><input type=\"text\" placeholder=\"Type a name of behavior\" class=\"newInput\"><a class=\"delete\" href=\"#\"></a><a class=\"edit\" href=\"#\"></a></div><div class=\"m_buttons\"><div class=\"m_action\">Add new action</div></div></div></div>";
    
    var ethogram;

    var method = {
        initialize: function(params) {

            self.ethogram = $(this);
            
            $(self.ethogram).prepend(inactive);
            

            $.ajax({url: params.url}).done(method.buildEhtogram);

            //method.inactiveTrigger($(".ma_block.inactive:last"));

//            var initializedelement = $(".ma_block.inactive > .m_slide > .m_text > input", this);
            //          initializedelement.on('keydown', function () {
            //            method.cloneInactive(this);
            //      }).enterKey();
            return this;
        },
        buildEhtogram: function(data) {

            $.each(data, function(i, value) {
                method.addContainer(i)
            });


        },
        addContainer: function(textval) {

            //var header = title;
            //var x = $(self.ethogram).prepend(header);
            $($('.m_title',title).text(textval)).prependTo($(self.ethogram));

            //console.log(x);

        },
        inactiveTrigger: function(element) {
            $(".m_text > input", element).on('keypress', function() {

                method.cloneInactive(this);

                return this;
            })
        },
        cloneInactive: function(initializedelement) {

            var element = $(".ma_block.inactive:last");
            var newelement = $(element).clone().appendTo('.manage_blocks');
            $(".m_text > input", element).off('keypress').on('keypress', function() {
                if (event.which === 13) {
                    var savebutton = $(".m_action", element);
                    $(savebutton).click();
                }
            });
            method.inactiveTrigger(newelement);
            method.activateContainer(element);


            return this;
        },
        activateContainer: function(element) {

            element.removeClass("inactive");
            var savebutton = $(".m_action", element);
            var deletebutton = $(".m_text .delete", element);
            var editbutton = $(".m_text .edit", element);
            $(deletebutton).on('click', function() {
                $(element).remove()
            });
            $(editbutton).hide();
            $(savebutton).unwrap().text("Save").on('click', function() {
                var textval = $(".m_text > input", element).val();
                method.saveContainer({subject: textval, position: element.index()});
                method.addTitle(element, textval);
            });

            return this;
        },
        saveContainer: function(values) {

            $.post("ethogramcontainer", values, function(data) {


            });

            return this;
        },
        addTitleOLD: function(element, textval) {

            var newelement = $(element).prepend(title);
            $(".m_action", element).remove();
            $(".m_title", newelement).text(textval);


            $(".m_text", element).remove();
            var addbehavior = $(".m_slide", element).prepend(button);
            $(".m_slide", element).sortable({
                connectWith: ".m_slide",
                items: 'div:not(.m_action)'});

            $(".m_action", addbehavior).on('click', function() {
                $(".m_action", element).before(field)
            });
            $(".m_title_edit > .delete", element).on('click', function() {
                $(element).remove();
            });
            $(".m_title_edit > .edit", element).on('click', function() {
                var text = $(".m_title", element).text();
                $(".m_title", element).remove();
                $(".m_title_wrapper", element).prepend(field);
            });
            return this;
        }
    };

    $.fn.ethogram = function(call) {
        if (method[call]) {
            return method[call].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof call === 'object' || !call) {
            return method.initialize.apply(this, arguments);
        } else {
            $.error('Method ' + call + ' not found in jQuery.test');
        }
    };
    $.fn.enterKey = function(fnc) {
        return this.each(function() {
            $(this).keypress(function(ev) {
                var keycode = (ev.keyCode ? ev.keyCode : ev.which);
                if (keycode == '13') {
                    fnc.call(this, ev);
                }
            })
        })
    }
})(jQuery);


