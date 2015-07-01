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
    var title = "<div class=\"m_title_wrapper\"><div class=\"m_title\"></div></div>";
    var buttonClass = 'm_action';
    var containerclass = 'ma_block';
    var boxclass = 'm_slide';
    var textwrapperclass = 'm_text';
    var subjectInput = $('<input/>', {type: 'text', class: 'subjectInput'});

    var ethogram;

    var method = {
        initialize: function(params) {

            self.ethogram = $(this);
            var inactive = $('<div/>', {class: containerclass + ' inactive'})
                    .prepend($('<div/>', {class: boxclass})
                            .prepend($('<div/>', {class: textwrapperclass})
                                    .prepend($('<input/>', {type: 'text', class: 'newInput', placeholder: 'Type a name of behavior'}))
                                    .on('keypress', method.activateContainer))
                            .append($('<div/>', {class: 'm_buttons'})
                                    .prepend($('<div/>', {class: buttonClass}).text('Add new action')))
                            );
            $(self.ethogram).prepend(inactive);


            $.ajax({url: params.url}).done(method.buildEhtogram);

            return this;
        },
        buildEhtogram: function(data) {



            $.each(data, method.addContainer);


        },
        addContainer: function(container, containervalues) {
            var collapseButton = $('<a/>', {class: 'collapseL'}).on('click', function() {
            });
            var deleteButton = $('<a/>', {class: 'delete fa-trash-o'}).on('click', function() {
                method.editContainer({id: container.id, action: 'delete'});
            });
            var editButton = $('<a/>', {class: 'edit fa-edit'}).on('click', function() {
            });
            var dragButton = $('<a/>', {class: 'drag fa-arrows'});
            var successButton = $('<a/>', {class: 'success fa-check'});

            var buttonsRow = $('<div/>', {class: 'm_title_edit'})
                    .prepend(dragButton)
                    .prepend(successButton)
                    .prepend(editButton)
                    .prepend(deleteButton)
                    .prepend(collapseButton);

            var containerelement = $('<div/>', {class: containerclass}).insertBefore($('.inactive'));

            //add button and make tiles sortable;

            var newActionButton = $('<div/>', {class: buttonClass})
                    .text('New Behavior')
                    .on('click', function() {
                        $('<div/>', {class: textwrapperclass}).insertBefore($(this))
                                .prepend($('<input>', {type: 'text', class: 'newInput'}))
                                .prepend($('<a/>', {class: 'delete'}).on('click', function() {
                                    $(this).closest('div').remove()
                                }));
                    });
            //createa a button that adds an empty text field
            newActionButton.prependTo($('<div/>', {class: boxclass}).sortable({
                connectWith: "." + boxclass,
                items: 'div:not(.m_action)'}).prependTo(containerelement));

            var subject =  $(subjectInput).val(containervalues.name);
            console.log(subject);
            var title = $('<div/>', {class: 'm_title_wrapper'})
                    .prepend($('<div/>', {class: 'm_title'})
                            .prepend(subject))
                    .append(buttonsRow)
                    ;
// placeholder: containervalues.name, value: containervalues.name,
            $(title).prependTo(containerelement);

            $.each(containervalues.values, function(i, value) {
                method.addBehavior(value, containerelement)
            })
            return this;

        },
        addBehavior: function(behavior, containerelement) {

            var deleteButton = $('<a/>', {class: 'delete'}).on('click', function() {

            });
            var editButton = $('<a/>', {class: 'edit'}).on('click', function() {
                console.log(behavior.id)
            });
            var buttonWrapper = $('<div/>', {class: textwrapperclass})
                    .prependTo($('.' + boxclass, containerelement));
            $('<input/>', {type: 'text', placeholder: behavior.name, value: behavior.name, class: 'newInput'}).
                    prependTo(buttonWrapper)
                    .after(editButton)
                    .after(deleteButton);

            return this;

        },
        editContainer: function(params) {


        },
        activateContainer: function(params) {
            var element = $($(params.target).closest('.inactive'));
            $(element).clone().appendTo(self.ethogram);
            $(element).prepend(title);
            //var ;

            console.log(container);



            //  method.addContainer('', );

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
        activateContainerOLD: function(element) {

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


