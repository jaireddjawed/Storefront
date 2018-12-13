$(document).ready(function () {
  $('form').validate({
    rules: {
      'street-address': {
        required: true,
      },
      city: {
        required: true,
      },
      state: {
        required: true,
      },
      'postal-code': {
        required: true,
      },
      'card-number': {
        required: true,
      },
      'exp-month': {
        required: true,
      },
      'exp-year': {
        required: true,
      },
      cvc: {
        required: true
      },
    },
    messages: {
      'street-address': {
        required: 'Please enter your street address.',
      },
      city: {
        required: 'Please enter your city.',
      },
      state: {
        required: 'Please enter your state.',
      },
      'postal-code': {
        required: 'Please enter your postal code.',
      },
      'card-number': {
        required: 'Please enter your card number.',
      },
      'exp-month': {
        required: 'Please enter your expiration month.',
      },
      'exp-year': {
        required: 'Please enter your expiration year.',
      },
      cvc: {
        required: 'Please enter your security code.'
      },
    },
    submitHandler: function () {
      Stripe.card.createToken({
        number: document.querySelector('[name="card-number"]').value,
        cvc: document.querySelector('[name="cvc"]').value,
        exp_month: document.querySelector('[name="exp-month"]').value,
        exp_year: document.querySelector('[name="exp-year"]').value
      }, stripeResponseHandler);
    },
  })
});

function stripeResponseHandler(status, response) {
  if (response.error) {
    alert(response.error.message);
  } else {
    var form = document.getElementsByTagName('form')[0];
    var token = response.id;

    // Append Stripe Token To Form
    var createStripeInput = document.createElement('input');
    createStripeInput.type = 'hidden';
    createStripeInput.name = 'stripe-token';
    createStripeInput.value = token;

    form.appendChild(createStripeInput);

    // Remove Card Info From card form so it wont be submitted to the server
    document.querySelector('[name="card-number"]').value = '';
    document.querySelector('[name="exp-month"]').value = '';
    document.querySelector('[name="exp-year"]').value = '';
    document.querySelector('[name="cvc"]').value = '';

    form.submit();
  }
}
