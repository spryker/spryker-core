<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyMarketplacePayment\Communication\Plugin\Payment;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Payment\Dependency\Plugin\Payment\PaymentMethodFilterPluginInterface;

/**
 * @method \Spryker\Zed\DummyMarketplacePayment\Business\DummyMarketplacePaymentFacadeInterface getFacade()
 * @method \Spryker\Zed\DummyMarketplacePayment\DummyMarketplacePaymentConfig getConfig()
 */
class MerchantProductItemPaymentMethodFilterPlugin extends AbstractPlugin implements PaymentMethodFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - If not all order items contain of product reference, then filters dummy marketplace payment methods out.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(
        PaymentMethodsTransfer $paymentMethodsTransfer,
        QuoteTransfer $quoteTransfer
    ): PaymentMethodsTransfer {
        return $this->getFacade()->filterPaymentMethods($paymentMethodsTransfer, $quoteTransfer);
    }
}
