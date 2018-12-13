$(document).ready(function () {
  $('.attribute').change(function (event) {
    var index = $('.attribute').index(this);
    var attrValue = event.target.value;

    var setAttrCookies = 'attribute-' + index + '=' + attrValue;
    document.cookie = setAttrCookies;

    fetch('/pages/view-product/product-price.php', {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
      },
    })
      .then((response) => {
        return response.text()
      })
      .then((productPrice) => {
        var price = document.getElementById('price-div');
        price.innerHTML = '$' + (productPrice / 100).toFixed(2);
      });
  });
});
