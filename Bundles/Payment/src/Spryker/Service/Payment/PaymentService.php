<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Payment;

use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\Payment\PaymentServiceFactory getFactory()
 */
class PaymentService extends AbstractService implements PaymentServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    public function getPaymentSelectionKey(PaymentTransfer $paymentTransfer): string
    {
        return $this->getFactory()->createPaymentMethodKeyExtractor()->getPaymentSelectionKey($paymentTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    public function getPaymentMethodKey(PaymentTransfer $paymentTransfer): string
    {
        return $this->getFactory()->createPaymentMethodKeyExtractor()->getPaymentMethodKey($paymentTransfer);
    }
}
