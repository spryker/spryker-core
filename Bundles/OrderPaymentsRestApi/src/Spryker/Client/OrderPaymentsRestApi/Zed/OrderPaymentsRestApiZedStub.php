<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OrderPaymentsRestApi\Zed;

use Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer;
use Spryker\Client\OrderPaymentsRestApi\Dependency\Client\OrderPaymentsRestApiToZedRequestClientInterface;

class OrderPaymentsRestApiZedStub implements OrderPaymentsRestApiZedStubInterface
{
    /**
     * @var \Spryker\Client\OrderPaymentsRestApi\Dependency\Client\OrderPaymentsRestApiToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\OrderPaymentsRestApi\Dependency\Client\OrderPaymentsRestApiToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(OrderPaymentsRestApiToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer $updateOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer
     */
    public function updateOrderPayment(
        UpdateOrderPaymentRequestTransfer $updateOrderPaymentRequestTransfer
    ): UpdateOrderPaymentResponseTransfer {
        /** @var \Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer $updateOrderPaymentResponseTransfer */
        $updateOrderPaymentResponseTransfer = $this->zedRequestClient
            ->call('/order-payments-rest-api/gateway/update-order-payment', $updateOrderPaymentRequestTransfer);

        return $updateOrderPaymentResponseTransfer;
    }
}
