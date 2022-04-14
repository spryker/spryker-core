<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Payment;

use Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer;
use Generated\Shared\Transfer\PaymentAuthorizeResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Payment\PaymentFactory getFactory()
 */
class PaymentClient extends AbstractClient implements PaymentClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAuthorizeResponseTransfer
     */
    public function authorizeForeignPayment(
        PaymentAuthorizeRequestTransfer $paymentAuthorizeRequestTransfer
    ): PaymentAuthorizeResponseTransfer {
        return $this->getFactory()
            ->createPaymentRequestExecutor()
            ->authorizeForeignPayment($paymentAuthorizeRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createZedStub()->getAvailableMethods($quoteTransfer);
    }
}
