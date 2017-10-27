$(function() {
  $(".slb").simplebox({
    darkMode: true
  });
  $('#rating').raty({
    path: '/images/',
    target : "[name='rate']",
    targetType: 'score',
    targetKeep : true
  });

  $('.rev-rate').raty({
    readOnly: true,
    half:  true,
    path: '/images/',
    score: function() {
      return $(this).attr('data-score');
    }
  });
});