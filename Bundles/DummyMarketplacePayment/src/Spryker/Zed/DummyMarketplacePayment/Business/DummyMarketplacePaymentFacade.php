<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyMarketplacePayment\Business;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\DummyMarketplacePayment\Business\DummyMarketplacePaymentBusinessFactory getFactory()
 */
class DummyMarketplacePaymentFacade extends AbstractFacade implements DummyMarketplacePaymentFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacment. `MerchantProductItemPaymentMethodFilterPlugin` directly accesses models, eliminating the need to expose business logic that was intedned to remain internal.
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
        return $this->getFactory()
            ->createPaymentMethodFilter()
            ->filterPaymentMethods($paymentMethodsTransfer, $quoteTransfer);
    }
}
