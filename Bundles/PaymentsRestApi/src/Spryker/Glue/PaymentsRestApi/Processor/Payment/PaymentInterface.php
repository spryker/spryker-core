<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\Payment;

use Generated\Shared\Transfer\RestPreOrderPaymentCancellationRequestAttributesTransfer;
use Generated\Shared\Transfer\RestPreOrderPaymentRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface PaymentInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestPreOrderPaymentRequestAttributesTransfer $restPreOrderPaymentRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function initializePreOrderPayment(
        RestRequestInterface $restRequest,
        RestPreOrderPaymentRequestAttributesTransfer $restPreOrderPaymentRequestAttributesTransfer
    ): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestPreOrderPaymentCancellationRequestAttributesTransfer $restPreOrderPaymentCancellationRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function cancelPreOrderPayment(
        RestRequestInterface $restRequest,
        RestPreOrderPaymentCancellationRequestAttributesTransfer $restPreOrderPaymentCancellationRequestAttributesTransfer
    ): RestResponseInterface;
}
