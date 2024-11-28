<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\PreOrderPaymentResponseTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface PaymentMethodRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     *
     * @return array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     */
    public function createRestPaymentMethodsResources(RestCheckoutDataTransfer $restCheckoutDataTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer $preOrderPaymentResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createPaymentCancellationsRestResponse(
        PreOrderPaymentResponseTransfer $preOrderPaymentResponseTransfer
    ): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer $preOrderPaymentResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createPaymentsRestResponse(PreOrderPaymentResponseTransfer $preOrderPaymentResponseTransfer): RestResponseInterface;
}
