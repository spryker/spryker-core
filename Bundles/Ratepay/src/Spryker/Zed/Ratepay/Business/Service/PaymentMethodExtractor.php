<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Service;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Ratepay\Business\Exception\NoPaymentMethodException;

class PaymentMethodExtractor implements PaymentMethodExtractorInterface
{
    /**
     * @var array
     */
    protected $paymentMethodsMapping;

    /**
     * @param array $paymentMethodsMapping
     */
    public function __construct(array $paymentMethodsMapping)
    {
        $this->paymentMethodsMapping = $paymentMethodsMapping;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @throws \Spryker\Zed\Ratepay\Business\Exception\NoPaymentMethodException
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null
     */
    public function extractPaymentMethod(QuoteTransfer $quoteTransfer)
    {
        if (!$quoteTransfer->getPayment()) {
            return null;
        }
        $payment = $quoteTransfer->getPayment();
        $paymentMethodName = $payment->getPaymentMethod();
        if (!isset($this->paymentMethodsMapping[$paymentMethodName])) {
            throw new NoPaymentMethodException();
        }
        $paymentMethodGet = 'get' . ucfirst($this->paymentMethodsMapping[$paymentMethodName]);

        return $payment->$paymentMethodGet();
    }
}
