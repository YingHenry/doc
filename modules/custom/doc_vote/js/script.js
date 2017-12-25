(function ($, Drupal) {
  Drupal.behaviors.doc_vote = {
    attach: function (context, settings) {
      $('.upvote', context).once('docUpvoteBehavior').each(function () {
        $(this).click(function() {
          var nid = $(this).text();
          var messageDiv = $(this).parent().siblings('.message');

          $.post('/vote/nojs/1/' + nid, null, function(response) {
            messageDiv.text(response.message);
          });
        });
      });

      $('.downvote', context).once('docDownvoteBehavior').each(function () {
        $(this).click(function() {
          var nid = $(this).text();
          var messageDiv = $(this).parent().siblings('.message');

          $.post('/vote/nojs/0/' + nid, null, function(response) {
            messageDiv.text(response.message);
          });
        });
      });
    }
  };
})(jQuery, Drupal);