/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */;


(function ($) {
    var buttonClass = 'm_action';
    var containerclass = 'ma_block';
    var boxclass = 'm_slide';
    var textwrapperclass = 'm_text';


    var subjectInput = {type: '<input/>', values: {type: 'text', class: 'subjectInput'}};
    var behaviorInput = {type: '<input/>', values: {type: 'text', class: 'newInput'}};

    var collapseButton = {type: '<a/>', values: {class: 'collapseL'}};
    var deleteButton = {type: '<a/>', values: {class: 'delete fa-trash-o'}};
    var editButton = {type: '<a/>', values: {class: 'edit fa-edit'}};
    var dragButton = {type: '<a/>', values: {class: 'drag fa-arrows'}};
    var successOkButton = {type: '<a/>', values: {class: 'success ok fa-check'}};
    var successPendingButton = {type: '<a/>', values: {class: 'success pendning fa-check'}};
    var LoadingButton = {type: '<a/>', values: {class: 'loadingIcon fa-spinner fa-pulse'}};

    var prevPagesOrder = [];
    var ethogram;

    var method = {
        initialize: function (params) {

            self.ethogram = $(this);

            $(".manage_blocks").sortable({
                items: '.ma_block:not(.inactive)',
                handle: '.drag',
                start: function (event, ui) {
                    prevPagesOrder = $(this).sortable('toArray');
                },
                stop: function (event, ui) {

                    var currentOrder = $(this).sortable('toArray');
                    var first = ui.item[0].id;
                    var second = currentOrder[prevPagesOrder.indexOf(first)];
                    if (first == second) {
                        return;
                    }
                    method.saveContainer($('#' + first));
                    method.saveContainer($('#' + second));
                }

            });

            $(".manage_blocks").disableSelection();


            var inactive = $('<div/>', {class: buttonClass + ' inactive'})
                    .text('Add new section')
                    .on('click', function () {
                        method.addContainer('new', {name: '', values: {}})
                        $('.subjectInput').last().focus();
//                        $('.success.pendning').last().on('click', function(event)
//                        {
//                            var element = $(event.target).parent().parent().parent();
//                            var value = $('.subjectInput', element).val();
//
//                            method.saveContainer(element, value);
//                        }
                    });

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
                        .prepend(method.createElement(successPendingButton).on('click', function (event) {
                            method.saveContainer(method.getContainer(event))
                        }))
                        .prepend($(method.createElement(deleteButton)
                                .on('click', function (event) {
                                    method.askQuestion(method.deleteContainer, method.getContainer(event))
                                })
                                ))

            } else {
                $(buttonsRow).prepend(method.createElement(dragButton))
//                    .prepend(successButton)
                        .prepend(method.createElement(editButton).on('click', function (event) {
                            method.editContainer(method.getContainer(event))
                        }))
                        .prepend($(method.createElement(deleteButton)
                                .on('click', function (event) {
                                    method.askQuestion(method.deleteContainer, method.getContainer(event))
                                })
                                ))
                        .prepend(method.createElement(collapseButton));

            }

            var containerelement = $('<div/>', {class: containerclass, id: containervalues.id})
                    .insertBefore($('.inactive'));

            var title = method.createTitle($(method.createElement(subjectInput)
                    .on('focusin', function (event) {
                        method.editContainer(method.getContainer(event))
                    }))
                    .on('keypress', function (event) {
                        if (event.which == 13) {
                            method.saveContainer(method.getContainer(event))
                        }
                    })
                    .val(containervalues.name)
                    .attr('placeholder', containervalues.name)).append(buttonsRow);

            $(title).prependTo(containerelement);

            var buttonsContainer = $('<div/>', {class: boxclass}).sortable({
                connectWith: "." + boxclass,
                items: 'div:not(.m_action)',
                handle: '.drag'
            });



            //add button and make tiles sortable;

            var newActionButton = $('<div/>', {class: buttonClass})
                    .text('New Behavior')
                    .on('click', function () {
                        $('<div/>', {class: textwrapperclass}).insertBefore($(this))
                                .prepend($('<input>', {type: 'text', class: 'newInput'}))
                                .prepend(method.createElement(successPendingButton))
                                .prepend($(method.createElement(deleteButton)
                                        .on('click', function (event) {
                                            method.askQuestion(method.deleteBehavior, method.getContainer(event))
                                        })
                                        ));

                    });

            if ($(containervalues.values).size() > 1) {
                $.each(containervalues.values, function (i, value) {

                    $(buttonsContainer).prepend(method.addBehavior(value));
                })
            }
            $(buttonsContainer).append(newActionButton);
            $(containerelement).append(buttonsContainer);


            return this;

        },
        createElement: function (elementObject) {
            return $(elementObject.type, elementObject.values);
        },
        addBehavior: function (behavorvalues) {

            if (typeof behavorvalues.name === 'undefined') {
                var name = '';
                var id = '';
                var buttonsRow = $(method.createElement(successPendingButton))
                        .after(method.createElement(deleteButton));
            } else {
                var name = behavorvalues.name;
                var id = behavorvalues.id;

                var buttonsRow = $(method.createElement(deleteButton))
                        .add(method.createElement(editButton).on('click',
                                function (event) {
                                    method.editBehavior(method.getBehaviorsBox(event))
                                }
                        ))
                        .add(method.createElement(dragButton));
            }

            var behavior = $('<div/>', {class: textwrapperclass, id: id})
                    .prepend(method.createElement(behaviorInput)
                            .val(name)
                            .attr('placeholder', name)).append(buttonsRow);


            return behavior;
        },
        createTitle: function (inputField) {

            return $('<div/>', {class: 'm_title_wrapper'})
                    .prepend($('<div/>', {class: 'm_title'})
                            .prepend(inputField))
        },
        editContainer: function (container) {

            console.log(container);
            $('.m_title_edit', container).empty()
                    .prepend(method.createElement(successPendingButton).on('click', function (event) {
                        method.saveContainer(method.getContainer(event))
                    }))
                    .prepend($(method.createElement(deleteButton)
                            .on('click', function (event) {
                                method.askQuestion(method.deleteContainer, method.getContainer(event))
                            })
                            ));

            $('.subjectInput', container).off('focusin').focus();


        },
        saveContainer: function (container) {

            var id = '';
            if (typeof $(container).attr('id') !== "undefined") {
                id = $(container).attr('id');
            }
            $('.m_title_edit', container).empty()
                    .prepend(method.createElement(LoadingButton));
            var value = $('.subjectInput', container).val();



            $.ajax({method: "POST", url: 'ethogramcontainer', data: {subject: value, position: $(container).index(), container_id: id}})
                    .done(function (msg) {
                        $(container).attr('id', msg.data.container_id);
                        $('.subjectInput', container).blur();

                        $('.m_title_edit', container)
                                .prepend(method.createElement(dragButton))
                                .prepend(method.createElement(editButton).on('click', function (event) {
                                    method.editContainer(method.getContainer(event))
                                }))
                                .prepend(method.createElement(deleteButton)
                                        .on('click', function (event) {
                                            method.askQuestion(method.deleteContainer, event)
                                        }))

                                .prepend(method.createElement(collapseButton));
                        $('.subjectInput', container).on('focusin', function (event) {
                            method.editContainer(method.getContainer(event))
                        });

                        $('.loadingIcon', container)
                                .fadeOut('slow', function () {
                                    $(this).before(method.createElement(successOkButton));
                                    $('.success.ok').fadeOut(1500);
                                });
                    });

        },
        editBehavior: function (behaviorsBox) {

            $('.newInput', behaviorsBox).nextAll().remove();
            $(behaviorsBox)
                    .append(method.createElement(deleteButton)
                            .on('click', function (event) {
                                method.askQuestion(method.deleteContainer, event)
                            }))
                    .append(method.createElement(successPendingButton).on('click', function (event) {
                        method.saveBehavior(method.getBehaviorsBox(event))
                    }));


            $('.newInput', behaviorsBox).off('focusin').focus();


        },
        saveBehavior: function (behaviorsBox) {

            var id = '';
            if (typeof $(behaviorsBox).attr('id') !== "undefined") {
                id = $(behaviorsBox).attr('id');
            }
            var container_id = $(behaviorsBox).closest('.' + containerclass).attr('id');

            var value = $('.newInput', behaviorsBox).val();
            $.ajax({method: 'POST', url: 'ethogrambehavior', data: {element_name: value, position: $(behaviorsBox).index(), element_id: id, container_id: container_id}})
                    .done(function (msg) {
                        $('.newInput', behaviorsBox).blur();
                        $('.newInput', behaviorsBox).nextAll().remove();
                        $(behaviorsBox)
                                .apppend(method.createElement(editButton).on('click',
                                function (event) {
                                    method.editBehavior(behaviorsBox)
                                }
                        ))
                        .apppend(method.createElement(dragButton));

                    });

        },
        askQuestion: function (eventFunc, event) {
            console.log(event);

            $('<div></div>', {class: 'closePopup'}).appendTo('body')
                    .html('<div><h6>Are you sure you want to delete container?</h6></div>')
                    .dialog({
                        modal: true,
                        dialogClass: 'closePopup',
                        title: 'Delete',
                        zIndex: 10000,
                        autoOpen: true,
                        width: 'auto',
                        resizable: false,
                        buttons: {
                            Yes: function () {
                                eventFunc(event);
                                $(this).dialog("close");
                            },
                            No: function () {
                                $(this).dialog("close");
                            }
                        },
                        close: function (event, ui) {
                            $(this).remove();
                        }
                    });
        },
        deleteContainer: function (container) {
            if (typeof $(container).attr('id') !== 'undefined')
            {
                var container_id = $(container).attr('id');
                $.ajax({url: 'deleteethogramcontainer', method: 'POST', data: {container_id: container_id}})
                        .done(function (msg) {
                            if (msg.transaction === 'success') {
                                $(container).fadeOut(1000, function () {
                                    $(this.remove())
                                });
                            }
                        });
            } else {
                $(container).fadeOut(1000, function () {
                    $(this.remove())
                })
            }
        },
        getContainer: function (event) {

            var container = $(event.target).closest('.' + containerclass);

            return container;
        },
        getBehaviorsBox: function (event) {
            return $(event.target).parent();
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


