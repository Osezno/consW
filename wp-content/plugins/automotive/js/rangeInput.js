(function($) {
function rangeInput(){
  $('#Minprice').html($('input:price .original').val());
  $("#Maxprice").html($("input:price .ghost").val());
// alert('test'); 
  var chose = $(this).val();

//alert(chose); 
  $.updateListingResults(1);
}
})(jQuery);