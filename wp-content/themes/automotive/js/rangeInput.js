
function rangeInput(){
var value2 = document.getElementById('priceDiv').children[2].value.split(",");
 document.getElementById('Minprice').innerHTML = "$" + value2[0];
  document.getElementById("Maxprice").innerHTML = "$" + value2[1];

}


function rangeInputYear(){
var value2 = document.getElementById('yearDiv').children[2].value.split(",");
 document.getElementById('Minyear').innerHTML = value2[0];
  document.getElementById("Maxyear").innerHTML = value2[1];

}