(function ($, Drupal) {
  Drupal.behaviors.doc_subscription = {
    attach: function (context, settings) {
      var uid = drupalSettings.doc_subscription.authorId;

      $('#subscription').click(function() {
        $.post('/toggle-subscription/nojs/' + uid, null, function(response) {
          $('#subscription').text(response.text);
        });
      });
    }
  };
})(jQuery, Drupal);