$(function() {
//写真がhoverされたとき
$('#photo-opener').hover(function(){
  $(this).fadeTo("fast", 0.7);
},
function(){
  $(this).fadeTo("fast", 1);
})

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

$('.rev_rate').raty({
readOnly: true,
half:  true,
path: '/images/',
score: function() {
return $(this).attr('data-score');
}
});

});