<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Dependency\Facade;

use Generated\Shared\Transfer\PaymentMethodCollectionTransfer;
use Generated\Shared\Transfer\PaymentMethodCriteriaTransfer;

class SalesPaymentMerchantToPaymentFacadeBridge implements SalesPaymentMerchantToPaymentFacadeInterface
{
    /**
     * @var \Spryker\Zed\Payment\Business\PaymentFacadeInterface
     */
    protected $paymentFacade;

    /**
     * @param \Spryker\Zed\Payment\Business\PaymentFacadeInterface $paymentFacade
     */
    public function __construct($paymentFacade)
    {
        $this->paymentFacade = $paymentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodCriteriaTransfer $paymentMethodCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCollectionTransfer
     */
    public function getPaymentMethodCollection(PaymentMethodCriteriaTransfer $paymentMethodCriteriaTransfer): PaymentMethodCollectionTransfer
    {
        return $this->paymentFacade->getPaymentMethodCollection($paymentMethodCriteriaTransfer);
    }

    /**
     * @param string $paymentProvider
     * @param string $paymentMethod
     *
     * @return string
     */
    public function generatePaymentMethodKey(string $paymentProvider, string $paymentMethod): string
    {
        return $this->paymentFacade->generatePaymentMethodKey($paymentProvider, $paymentMethod);
    }
}
