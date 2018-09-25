(function($) {
  $.fn.slidetoggle = function(options) {
    var settings = $.extend({
        className: 'slidetoggle',
        stateText: ['no', 'yes']
      }, options || {})
      , eventName = settings.className + ':click'
      , $template = $('<div class="' +
          settings.className + '"><div></div><div></div></div>');

    if(!$.fn.slidetoggle.init) {
      $(document).on(eventName, function(e) {
        var $toggle = $(e.target);
        $toggle.prev().attr('checked', function(i, attr) {
          $toggle.toggleClass('checked', attr)
            .find(':first-child').text(settings.stateText[!attr-0]);
          return !attr;
        });
      });

      $(document).on('click', 'div.' + settings.className, function() {
        $(this).trigger(eventName);
      });

      $(document).on('click', 'label', function() {
        $('div[for=' + $(this).attr('for') + ']').click();
      });
    }

    $.fn.slidetoggle.init = true;

    return this.each(function() {
      var $checkbox = $(this)
        , $toggle = $template.clone();

      $checkbox.hide()
        .attr('checked', function(i, attr) {
          attr = attr || false;
          if(attr == 'checked') attr = true;
          $toggle.attr('for', $checkbox.attr('id'))
            .toggleClass('checked', attr)
              .find(':first-child').text(settings.stateText[attr-0]);
          return attr;
        })
        .after($toggle);
    });
  }
})(jQuery);
