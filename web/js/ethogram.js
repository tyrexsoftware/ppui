/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */;

(function($) {
    var method = {
        initialize: function() {
            console.log($(".ma_block.inactive > .m_slide > .m_text > input", this));
            $(".ma_block.inactive > .m_slide > .m_text > input",this).on('keydown', function() {method.cloneInactive(this);});
            return this;
        },
        cloneInactive: function() {
            var element = $(".ma_block.inactive:last");
            $(".ma_block.inactive:last").clone().appendTo(element);
            
            return this;
        },
        text: function(content) {
            $(this).text(content);
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
})(jQuery);
