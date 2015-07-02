/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */;

jQuery(document).ready(function () {
    $(".manage_blocks").sortable({
        items: '.ma_block:not(.inactive)',
        stop: function (event, ui) {
            console.log("New position: " + ui.item.index());
        }
    });
    $(".manage_blocks").disableSelection();
});

(function ($) {
    var buttonClass = 'm_action';
    var containerclass = 'ma_block';
    var boxclass = 'm_slide';
    var textwrapperclass = 'm_text';


    var subjectInput = {type: '<input/>', values: {type: 'text', class: 'subjectInput'}};

    var collapseButton = {type: '<a/>', values: {class: 'collapseL'}};
    var deleteButton = {type: '<a/>', values: {class: 'delete fa-trash-o'}};
    var editButton = {type: '<a/>', values: {class: 'edit fa-edit'}};
    var dragButton = {type: '<a/>', values: {class: 'drag fa-arrows'}};
    var successOkButton = {type: '<a/>', values: {class: 'success ok fa-check'}};
    var successPendingButton = {type: '<a/>', values: {class: 'success pendning fa-check'}};
    var LoadingButton = {type: '<a/>', values: {class: 'loadingIcon fa-spinner fa-pulse'}};


    var ethogram;

    var method = {
        initialize: function (params) {

            self.ethogram = $(this);
//            var inactive = $('<div/>', {class: containerclass + ' inactive'})
//                    .prepend($('<div/>', {class: boxclass})
//                            .prepend($('<div/>', {class: textwrapperclass})
//                                    .prepend($('<input/>', {type: 'text', class: 'newInput', placeholder: 'Type a name of behavior'}))
//                                    .on('keyup', method.activateContainer))
//                                    .prepend($('<div/>', {class: buttonClass}).text('Add new action')))
//                            );

            var inactive = $('<div/>', {class: buttonClass + ' inactive'})
                    .text('Add new section')
                    .on('click', function () {
                        method.addContainer('new', {name: '', values: {}})
                        $('.subjectInput').last().focus();
                        $('.success.pendning').last().on('click', function (event)
                        {
                            var element = $(event.target).parent().parent().parent();
                            var value = $('.subjectInput', element).val();

                            method.saveContainer(element, value);
                        }
                        )

                    });
            ;

            $(self.ethogram).prepend(inactive);


            $.ajax({url: params.url}).done(method.buildEhtogram);

            return this;
        },
        buildEhtogram: function (data) {



            $.each(data, method.addContainer);
            return this;


        },
        addContainer: function (type, containervalues) {

            var buttonsRow = $('<div/>', {class: 'm_title_edit'})
            if (type == 'new') {

                $(buttonsRow)
                        .prepend(method.createElement(successPendingButton))
                        .prepend(method.createElement(deleteButton));

            } else {
                $(buttonsRow).prepend(method.createElement(dragButton))
//                    .prepend(successButton)
                        .prepend(method.createElement(editButton))
                        .prepend(method.createElement(deleteButton))
                        .prepend(method.createElement(collapseButton));

            }

            var containerelement = $('<div/>', {class: containerclass, id: containervalues.id})
                    .insertBefore($('.inactive'));

            //add button and make tiles sortable;

            var newActionButton = $('<div/>', {class: buttonClass})
                    .text('New Behavior')
                    .on('click', function () {
                        $('<div/>', {class: textwrapperclass}).insertBefore($(this))
                                .prepend($('<input>', {type: 'text', class: 'newInput'}))
                                .prepend($('<a/>', {class: 'delete'}).on('click', function () {
                                    $(this).closest('div').remove()
                                }));
                    });
            //createa a button that adds an empty text field
            newActionButton.prependTo($('<div/>', {class: boxclass}).sortable({
                connectWith: "." + boxclass,
                items: 'div:not(.m_action)'}).prependTo(containerelement));


            var title = method.createTitle($(method.createElement(subjectInput))
                    .val(containervalues.name)
                    .attr('placeholder', containervalues.name)).append(buttonsRow);

            $(title).prependTo(containerelement);

            if ($(containervalues.values).size() > 1) {
                $.each(containervalues.values, function (i, value) {
                    method.addBehavior(value, containerelement)
                })
            }
            return this;

        },
        createElement: function (elementObject) {
            return $(elementObject.type, elementObject.values);
        },
        addBehavior: function (behavior, containerelement) {

            var deleteButton = $('<a/>', {class: 'delete'}).on('click', function () {

            });
            var editButton = $('<a/>', {class: 'edit'}).on('click', function () {
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
        createTitle: function (inputField) {

            return $('<div/>', {class: 'm_title_wrapper'})
                    .prepend($('<div/>', {class: 'm_title'})
                            .prepend(inputField))
        },
        editContainer: function (params) {


        },
        saveContainer: function (element, value) {

            var id = '';
            if ($(element).attr('id') !== "undefined") {
                id = $(element).attr('id');
            }
            $('.m_title_edit', element).empty()
                    .prepend(method.createElement(LoadingButton));



            $.ajax({method: "POST", url: 'ethogramcontainer', data: {subject: value, position: $(element).index(), container_id: ''}})
                    .done(function (msg) {
                        $(element).attr('id', msg.data.container_id);
                        $('.subjectInput', element).blur();

                        $('.m_title_edit', element)
                                .prepend(method.createElement(dragButton))
                                .prepend(method.createElement(editButton))
                                .prepend(method.createElement(deleteButton))
                                .on('click', method.deleteContainer(msg.data.container_id))
                                .prepend(method.createElement(collapseButton));

                        $('.loadingIcon', element)
                                .fadeOut('slow', function () {
                                    $(this).before(method.createElement(successOkButton));
                                    $('.success.ok').fadeOut(1500);
                                });
                    });

        }

    };

    $.fn.ethogram = function (call) {
        if (method[call]) {
            return method[call].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof call === 'object' || !call) {
            return method.initialize.apply(this, arguments);
        } else {
            $.error('Method ' + call + ' not found in jQuery.test');
        }
    };
    $.fn.enterKey = function (fnc) {
        return this.each(function () {
            $(this).keypress(function (ev) {
                var keycode = (ev.keyCode ? ev.keyCode : ev.which);
                if (keycode == '13') {
                    fnc.call(this, ev);
                }
            })
        })
    }
})(jQuery);


