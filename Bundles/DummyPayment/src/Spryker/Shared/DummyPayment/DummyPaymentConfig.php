<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\DummyPayment;

interface DummyPaymentConfig
{
    /**
     * @var string
     */
    public const PROVIDER_NAME = 'DummyPayment';
    /**
     * @var string
     */
    public const PAYMENT_METHOD_INVOICE = 'dummyPaymentInvoice';
    /**
     * @var string
     */
    public const PAYMENT_METHOD_CREDIT_CARD = 'dummyPaymentCreditCard';
    /**
     * @var string
     */
    public const PAYMENT_METHOD_NAME_INVOICE = 'invoice';
    /**
     * @var string
     */
    public const PAYMENT_METHOD_NAME_CREDIT_CARD = 'credit card';
}
