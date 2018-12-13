$(document).ready(function () {

});

var shippableCheckbox = document.querySelector('[name="shippable"]');
var productShippingStats = document.getElementById('product-shipping-stats');

shippableCheckbox.addEventListener('click', function () {
  if (shippableCheckbox.checked) productShippingStats.style.display = 'block';
  else productShippingStats.style.display = 'none';
});
