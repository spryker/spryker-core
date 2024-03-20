<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentDetail\Business;

use Generated\Shared\Transfer\PaymentCreatedTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesPaymentDetail\Business\SalesPaymentDetailBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesPaymentDetail\Persistence\SalesPaymentDetailRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesPaymentDetail\Persistence\SalesPaymentDetailEntityManagerInterface getEntityManager()
 */
class SalesPaymentDetailFacade extends AbstractFacade implements SalesPaymentDetailFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentCreatedTransfer $paymentCreatedTransfer
     *
     * @return void
     */
    public function handlePaymentCreated(PaymentCreatedTransfer $paymentCreatedTransfer): void
    {
        $this->getFactory()->createPaymentCreatedMessageHandler()->handlePaymentCreated($paymentCreatedTransfer);
    }
}
