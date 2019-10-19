<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface CartCodeRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartCodeOperationResultTransfer $cartCodeOperationResultTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildCartRestResponse(
        CartCodeOperationResultTransfer $cartCodeOperationResultTransfer,
        RestRequestInterface $restRequest
    ): RestResponseInterface;
}
