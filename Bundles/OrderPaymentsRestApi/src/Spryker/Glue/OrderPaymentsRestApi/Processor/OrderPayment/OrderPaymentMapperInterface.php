<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderPaymentsRestApi\Processor\OrderPayment;

use Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer;
use Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface OrderPaymentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\UpdateOrderPaymentRequestTransfer
     */
    public function mapRestOrderPaymentsAttributesTransferToUpdateOrderPaymentRequestTransfer(
        RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer
    ): UpdateOrderPaymentRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\UpdateOrderPaymentResponseTransfer $updateOrderPaymentResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer
     */
    public function mapUpdateOrderPaymentResponseTransferToRestOrderPaymentsAttributesTransfer(
        UpdateOrderPaymentResponseTransfer $updateOrderPaymentResponseTransfer
    ): RestOrderPaymentsAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapOrderPaymentResource(
        RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer
    ): RestResourceInterface;
}
