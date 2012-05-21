$(function(){
   
    // hide response area
    $('#db-check-response').hide();
   
    var form = $('form'),
        db_fieldset = $('#database-info'),
        db_check_button = $('#db-check');
   
   
    /*
     *      Check the Database Information
     */
    var db_check = function() {
        
        $('#db-check-response').fadeOut().find('.error').remove();
        
        db_check_button.attr('disabled','disabled');
       
        var data = '',
            inputs = db_fieldset.find('input');

        inputs.each(function(){
            data+= $(this).attr('name') + '=' + $(this).val() + '&';
        });
        data = data.substring(0,data.length-1);
        
        // fire off ajax
        $.ajax({
            type: 'POST',
            url: 'ajax/?install=dbcheck.php',
            data: data,
            dataType: 'json',
            cache: false,
            success: db_check_result
        });      
    };
    
    var db_check_result = function(response) {
        
        db_check_button.removeAttr('disabled');
        
        if(response.success === true) {
            db_check_button.removeClass('btn-warning').addClass('btn-success').html('Looks Good!');
        }
        else {
            db_check_button.removeClass('btn-success').addClass('btn-warning').html('Check Database Connection');
            
            $.each(response.messages, function(i, message){
                $('#db-check-response').append('<p class="align-center">'+message+'</p>').fadeIn();
            });
        }
    }
    
    // Bindings
    db_check_button.on('click', db_check);
    //form.on('submit',submit);
    
});


// CUSTOMIZED BOOTSTRAP-COLLAPSE.JS

/* =============================================================
 * bootstrap-collapse.js v2.0.0
 * http://twitter.github.com/bootstrap/javascript.html#collapse
 * =============================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================ */

!function( $ ){

  "use strict"

  var Collapse = function ( element, options ) {
  	this.$element = $(element)
    this.options = $.extend({}, $.fn.collapse.defaults, options)

    if (this.options["parent"]) {
      this.$parent = $(this.options["parent"])
    }

    this.options.toggle && this.toggle()
  }

  Collapse.prototype = {

    constructor: Collapse

  , dimension: function () {
      var hasWidth = this.$element.hasClass('width')
      return hasWidth ? 'width' : 'height'
    }

  , show: function () {
      var dimension = this.dimension()
        , scroll = $.camelCase(['scroll', dimension].join('-'))
        , actives = this.$parent && this.$parent.find('.in')
        , hasData

      if (actives && actives.length) {
        hasData = actives.data('collapse')
        actives.collapse('hide')
        hasData || actives.data('collapse', null)
      }

      this.$element[dimension](0)
      this.$element.parents('.accordion-group').addClass('selected');
      this.transition('addClass', 'show', 'shown')
      this.$element[dimension](this.$element[0][scroll])
      
      $('input[name="theme"]').val(this.$element[0].id);

    }

  , hide: function () {
      var dimension = this.dimension()
      this.reset(this.$element[dimension]())
      this.$element.parents('.accordion-group').removeClass('selected');
      this.transition('removeClass', 'hide', 'hidden')
      this.$element[dimension](0)
    }

  , reset: function ( size ) {
      var dimension = this.dimension()

      this.$element
        .removeClass('collapse')
        [dimension](size || 'auto')
        [0].offsetWidth

      this.$element.addClass('collapse')
    }

  , transition: function ( method, startEvent, completeEvent ) {
      var that = this
        , complete = function () {
            if (startEvent == 'show') that.reset()
            that.$element.trigger(completeEvent)
          }

      this.$element
        .trigger(startEvent)
        [method]('in')

      $.support.transition && this.$element.hasClass('collapse') ?
        this.$element.one($.support.transition.end, complete) :
        complete()
  	}

  , toggle: function () {
      this[this.$element.hasClass('in') ? 'hide' : 'show']()
  	}

  }

  /* COLLAPSIBLE PLUGIN DEFINITION
  * ============================== */

  $.fn.collapse = function ( option ) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('collapse')
        , options = typeof option == 'object' && option
      if (!data) $this.data('collapse', (data = new Collapse(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.collapse.defaults = {
    toggle: true
  }

  $.fn.collapse.Constructor = Collapse


 /* COLLAPSIBLE DATA-API
  * ==================== */

  $(function () {
    $('body').on('click.collapse.data-api', '[data-toggle=collapse]', function ( e ) {
      var $this = $(this), href
        , target = $this.attr('data-target')
          || e.preventDefault()
          || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '') //strip for ie7
        , option = $(target).data('collapse') ? 'toggle' : $this.data()
      $(target).collapse(option)
    })
  })

}( window.jQuery )


// CUSTOM PLUS TO MINUS TOGGLE
// -------------------------------

$(document).ready(function(){
    $('.expander').click(function(){
        $(this).find('i').toggleClass('icon-plus').toggleClass('icon-minus');
        
        var html = $(this).find('strong').html();
                
        if ($(this).find(':contains("more")').size() > 0)
        {
            html = html.replace('more', 'less');
            $(this).find('strong').html(html);
            return true;
        }
        if ($(this).find(':contains("less")').size() > 0)
        {
            html = html.replace('less', 'more');
            $(this).find('strong').html(html);
            return true;
        }
    });
});

