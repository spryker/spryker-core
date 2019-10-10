<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface CartCodeRestResponseBuilderInterface
{
    /**
     * @param CartCodeOperationResultTransfer $cartCodeOperationResultTransfer
     * @return RestResponseInterface
     */
    public function buildCartRestResponse(CartCodeOperationResultTransfer $cartCodeOperationResultTransfer): RestResponseInterface;
}
