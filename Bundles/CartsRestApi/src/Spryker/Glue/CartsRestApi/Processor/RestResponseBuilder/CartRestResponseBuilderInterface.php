<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface CartRestResponseBuilderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null $cartRestResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartRestResponse(?RestResourceInterface $cartRestResource = null): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createQuoteErrorResponse(QuoteResponseTransfer $quoteResponseTransfer): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartIdMissingError(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartNotFoundError(): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createFailedCreatingCartError(QuoteResponseTransfer $quoteResponseTransfer): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createFailedDeletingCartError(): RestResponseInterface;
}
