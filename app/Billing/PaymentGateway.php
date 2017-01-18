<?php

namespace App\Billing;

interface PaymentGateway
{
    public function charge($amount, $token);

    public function getValidTestToken();
}
