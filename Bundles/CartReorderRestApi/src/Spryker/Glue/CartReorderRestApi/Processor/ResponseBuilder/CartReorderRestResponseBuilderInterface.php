<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartReorderRestApi\Processor\ResponseBuilder;

use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface CartReorderRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildSuccessfulResponse(CartReorderResponseTransfer $cartReorderResponseTransfer, RestRequestInterface $restRequest): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer
     * @param string $locale
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildErrorResponse(CartReorderResponseTransfer $cartReorderResponseTransfer, string $locale): RestResponseInterface;

    /**
     * @param list<\Generated\Shared\Transfer\RestErrorMessageTransfer> $restErrorMessageTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildRequestValidationErrorResponse(array $restErrorMessageTransfers): RestResponseInterface;
}
