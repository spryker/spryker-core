<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyMarketplacePayment\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\DummyMarketplacePayment\DummyMarketplacePaymentConfig;

class PaymentMethodFilter implements PaymentMethodFilterInterface
{
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
        if ($this->hasItemsProductOfferReference($quoteTransfer)) {
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
            if (
                !$paymentMethodTransfer->getPaymentProvider()
                || $paymentMethodTransfer->getPaymentProvider()->getPaymentProviderKey() !== DummyMarketplacePaymentConfig::PAYMENT_PROVIDER_NAME
            ) {
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
    protected function hasItemsProductOfferReference(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getProductOfferReference()) {
                return true;
            }
        }

        return false;
    }
}
