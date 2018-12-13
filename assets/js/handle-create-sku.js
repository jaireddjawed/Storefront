$(document).ready(function () {
  $('form').validate({
    rules: {
      price: {
        required: true,
      },
      inventory: {
        required: true,
      },
      quantity: {
        required: true
      },
    },
    messages: {
      price: {
        required: 'Please create a price.',
      },
      inventory: {
        required: 'Please select the type of inventory.',
      },
      quantity: {
        required: 'Please enter a quantity.',
      },
    },
  });
});

document.querySelector('[name="inventory"]').addEventListener('change', function (event) {
  var inventoryType = event.target.value;
  var quantityInput = document.getElementById('quantity');

  if (inventoryType === 'infinite') {
    quantityInput.style.display = 'none';
  } else if (inventoryType === 'finite') {
    quantityInput.style.display = 'block';
  }
});
