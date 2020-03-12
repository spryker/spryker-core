<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyMarketplacePayment\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\DummyMarketplacePayment\DummyMarketplacePaymentConfig;

class PaymentMethodFilter implements PaymentMethodFilterInterface
{
    /**
     * @var \Spryker\Zed\DummyMarketplacePayment\DummyMarketplacePaymentConfig
     */
    protected $dummyMarketplacePaymentConfig;

    /**
     * @param \Spryker\Zed\DummyMarketplacePayment\DummyMarketplacePaymentConfig $dummyMarketplacePaymentConfig
     */
    public function __construct(DummyMarketplacePaymentConfig $dummyMarketplacePaymentConfig)
    {
        $this->dummyMarketplacePaymentConfig = $dummyMarketplacePaymentConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(
        PaymentMethodsTransfer $paymentMethodsTransfer,
        QuoteTransfer $quoteTransfer
    ): PaymentMethodsTransfer {
        if ($this->haveItemsProductOfferReference($quoteTransfer)) {
            return $paymentMethodsTransfer;
        }

        return $this->removeDummyMarketplacePaymentMethods($paymentMethodsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function removeDummyMarketplacePaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer): PaymentMethodsTransfer
    {
        $allowedPaymentMethods = new ArrayObject();

        foreach ($paymentMethodsTransfer->getMethods() as $paymentMethodTransfer) {
            if (!in_array($paymentMethodTransfer->getMethodName(), $this->dummyMarketplacePaymentConfig->getDummyMarketplacePaymentMethods())) {
                $allowedPaymentMethods->append($paymentMethodTransfer);
            }
        }

        return $paymentMethodsTransfer->setMethods($allowedPaymentMethods);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function haveItemsProductOfferReference(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getProductOfferReference()) {
                return false;
            }
        }

        return true;
    }
}
