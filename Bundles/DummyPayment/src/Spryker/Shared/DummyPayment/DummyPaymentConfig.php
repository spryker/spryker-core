<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\DummyPayment;

interface DummyPaymentConfig
{
    public const PROVIDER_NAME = 'DummyPayment';
    public const PAYMENT_METHOD_INVOICE = 'dummyPaymentInvoice';
    public const PAYMENT_METHOD_CREDIT_CARD = 'dummyPaymentCreditCard';
}
