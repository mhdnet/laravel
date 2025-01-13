<?php

namespace Tests\Feature;

use App\Constants\OrderStatus;

class PaymentTest extends StatementTest
{

    protected string $url = '/api/admin/payments/';

    protected string $status = OrderStatus::Shipped;
}
