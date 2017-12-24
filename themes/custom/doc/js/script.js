(function ($, Drupal) {
  Drupal.behaviors.doc = {
    attach: function (context, settings) {
      $('.doc-header', context).once('docHeaderBehavior').each(function () {
        $(this).click(function() {
        	$(this).siblings('.doc-body-footer').slideToggle();
        });
      });
    }
  };
})(jQuery, Drupal);