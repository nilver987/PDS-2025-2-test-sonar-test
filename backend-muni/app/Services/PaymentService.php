<?php

namespace App\Services;

class PaymentService
{
    private $paymentGateway;

    public function __construct($paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function processPayment($amount, $currency)
    {
        return $this->paymentGateway->process($amount, $currency);
    }
}
