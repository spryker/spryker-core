<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OrderPaymentsRestApi;

use Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\OrderPaymentsRestApi\OrderPaymentsRestApiFactory getFactory()
 */
class OrderPaymentsRestApiClient extends AbstractClient implements OrderPaymentsRestApiClientInterface
{
    /**
     * {@inheritDoc}
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
            ->createOrderPaymentsRestApiZedStub()
            ->updateOrderPayment($updateOrderPaymentRequestTransfer);
    }
}
