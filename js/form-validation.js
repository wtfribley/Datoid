// Custom jQuery Form Validator for DATOID

(function($){
    
    var methods = {
        setValidity: function(valid) {       
            if(valid) {
                $(this).removeClass('invalid');
                $(this).siblings('.validation-message').fadeOut();
            }
            else {
                $(this).addClass('invalid');
                $(this).siblings('.validation-message').fadeIn();
            }      
        }
    };
    
    $.fn.DaValidator = function() {
        var requireds = this.find('input[required]');
        
        if(requireds.length > 0) $('[type="submit"]').attr('disabled','disabled');

        requireds.on('blur', function(){
            methods['setValidity'].apply(this, [this.validity.valid]);            
        }).on('keyup', function(e){
            if(e.which != 9) // tabbing to the field shouldn't immediately mark it invalid
                methods['setValidity'].apply(this, [this.validity.valid]);

            this.checkValidity() ?
                $('button[type="submit"]').removeAttr('disabled') :
                $('button[type="submit"]').attr('disabled','disabled');
        });
    };
    
})(jQuery);

// call DaValidator on forms set to novalidate="custom"

$('document').ready(function(){
    $('form[novalidate="custom"]').DaValidator();
});