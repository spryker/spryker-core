<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiCartsRestApi\Processor\Cart;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MultiCartsRestApi\Dependency\Client\MultiCartsRestApiToPersistentCartClientInterface;

class MultipleQuoteCreator implements MultipleQuoteCreatorInterface
{
    /**
     * @var \Spryker\Glue\MultiCartsRestApi\Dependency\Client\MultiCartsRestApiToPersistentCartClientInterface
     */
    protected $persistentCartClient;

    /**
     * @param \Spryker\Glue\MultiCartsRestApi\Dependency\Client\MultiCartsRestApiToPersistentCartClientInterface $persistentCartClient
     */
    public function __construct(MultiCartsRestApiToPersistentCartClientInterface $persistentCartClient)
    {
        $this->persistentCartClient = $persistentCartClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(RestRequestInterface $restRequest, QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->persistentCartClient->createQuote($quoteTransfer);
    }
}
