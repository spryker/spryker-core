<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface PaymentMethodsRestResponseBuilderInterface
{
    /**
     * @param int $idPaymentMethod
     * @param \Generated\Shared\Transfer\RestPaymentMethodsAttributesTransfer $restPaymentMethodsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createRestPaymentMethodsResource(
        int $idPaymentMethod,
        RestPaymentMethodsAttributesTransfer $restPaymentMethodsAttributesTransfer
    ): RestResourceInterface;
}
