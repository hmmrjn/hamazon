$(function() {

$( ".photo" ).dialog({
  autoOpen: false,
  resizable: false,
  width:800,
  height:600,
  modal: true,
});
$( "#photo-opener" ).click(function() {
  $( ".photo" ).dialog( "open" );
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