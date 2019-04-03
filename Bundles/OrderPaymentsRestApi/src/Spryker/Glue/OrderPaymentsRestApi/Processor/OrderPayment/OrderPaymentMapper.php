<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderPaymentsRestApi\Processor\OrderPayment;

use Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer;
use Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer;

class OrderPaymentMapper implements OrderPaymentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer
     * @param \Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer $updateOrderPaymentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer
     */
    public function mapRestOrderPaymentsAttributesTransferToUpdateOrderPaymentRequestTransfer(
        RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer,
        UpdateOrderPaymentRequestTransfer $updateOrderPaymentRequestTransfer
    ): UpdateOrderPaymentRequestTransfer {
        return $updateOrderPaymentRequestTransfer->fromArray($restOrderPaymentsAttributesTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer $updateOrderPaymentResponseTransfer
     * @param \Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer
     */
    public function mapUpdateOrderPaymentResponseTransferToRestOrderPaymentsAttributesTransfer(
        UpdateOrderPaymentResponseTransfer $updateOrderPaymentResponseTransfer,
        RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer
    ): RestOrderPaymentsAttributesTransfer {
        return $restOrderPaymentsAttributesTransfer->fromArray($updateOrderPaymentResponseTransfer->toArray(), true);
    }
}
