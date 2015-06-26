(function($) {
    var method = {
        initialize: function() {
            console.log(this);
            return this;
        },
        show: function() {
            $(this).show();
            return this;
        },
        hide: function() {
            $(this).hide();
            return this;
        },
        text: function(content) {
            $(this).text(content);
            return this;
        }
    };

    $.fn.test = function(call) {
        if (method[call]) {
            return method[call].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof call === 'object' || !call) {
            return method.initialize.apply(this, arguments);
        } else {
            $.error('Method ' + call + ' not found in jQuery.test');
        }
    };
})(jQuery);

//.test('show').test('hide').test('show').test('text', 'blah-blah').test('hide').test('text', 'blah-blah-blah-blah').test('show');