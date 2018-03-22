<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\DummyPayment;

interface DummyPaymentConfig
{
    const PROVIDER_NAME = 'DummyPayment';
    const PAYMENT_METHOD_INVOICE = 'dummyPaymentInvoice';
    const PAYMENT_METHOD_CREDIT_CARD = 'dummyPaymentCreditCard';
}
