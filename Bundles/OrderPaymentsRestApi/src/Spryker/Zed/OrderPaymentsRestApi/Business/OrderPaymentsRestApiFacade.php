<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderPaymentsRestApi\Business;

use Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\OrderPaymentsRestApi\Business\OrderPaymentsRestApiBusinessFactory getFactory()
 */
class OrderPaymentsRestApiFacade extends AbstractFacade implements OrderPaymentsRestApiFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer $updateOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer
     */
    public function updateOrderPayment(
        UpdateOrderPaymentRequestTransfer $updateOrderPaymentRequestTransfer
    ): UpdateOrderPaymentResponseTransfer {
        return $this->getFactory()
            ->createOrderPaymentUpdater()
            ->updateOrderPayment($updateOrderPaymentRequestTransfer);
    }
}
