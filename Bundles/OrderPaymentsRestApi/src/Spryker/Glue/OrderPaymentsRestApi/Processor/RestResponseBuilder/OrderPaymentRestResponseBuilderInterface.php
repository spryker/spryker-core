<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderPaymentsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface OrderPaymentRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createOrderPaymentRestResponse(
        RestOrderPaymentsAttributesTransfer $restOrderPaymentsAttributesTransfer
    ): RestResponseInterface;

    /**
     * @param string $errorCode
     * @param int $status
     * @param string $errorMessage
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildErrorRestResponse(
        string $errorCode,
        int $status,
        string $errorMessage
    ): RestResponseInterface;
}
