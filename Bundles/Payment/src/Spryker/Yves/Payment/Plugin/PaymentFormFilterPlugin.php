<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Payment\Plugin;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Checkout\Dependency\Plugin\Form\SubFormFilterPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @deprecated Will be removed without replacement. The logic is moved to {@link \SprykerShop\Yves\CheckoutPage\Form\Steps\PaymentForm}.
 *
 * @method \Spryker\Yves\Payment\PaymentFactory getFactory()
 * @method \Spryker\Client\Payment\PaymentClientInterface getClient()
 */
class PaymentFormFilterPlugin extends AbstractPlugin implements SubFormFilterPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string>
     */
    public function getValidFormNames(QuoteTransfer $quoteTransfer)
    {
        return $this->collectPaymentMethodNames(
            $this->getClient()->getAvailableMethods($quoteTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return array<string>
     */
    protected function collectPaymentMethodNames(PaymentMethodsTransfer $paymentMethodsTransfer)
    {
        $paymentMethodNames = [];

        foreach ($paymentMethodsTransfer->getMethods() as $paymentMethodTransfer) {
            $paymentMethodNames[] = $paymentMethodTransfer->getPaymentMethodKey();
        }

        return $paymentMethodNames;
    }
}
