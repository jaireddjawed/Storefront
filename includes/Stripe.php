<?php
  require_once('EnvSetup.php');
  require_once('../../vendor/autoload.php');

  $stripeSecret = getenv('STRIPE_SECRET');
  \Stripe\Stripe::setApiKey($stripeSecret);

  function createCustomer($customerInfo)
  {
    $customer = \Stripe\Customer::create($customerInfo);
    return $customer;
  }

  function chargeCardFromStripeToken($chargeInfo)
  {
    $charge = \Stripe\Charge::create($chargeInfo);
    return $charge;
  }

  function swapCardOnFile($customerId, $token)
  {
    $swapCustomerCard = \Stripe\Customer::update($customerId, [
      'source' => $token
    ]);
    return $swapCustomerCard;
  }
