<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiCartsRestApi\Processor\Quote;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MultiCartsRestApi\Dependency\Client\MultiCartsRestApiToCartsRestApiClientInterface;

class MultipleQuoteCreator implements MultipleQuoteCreatorInterface
{
    /**
     * @var \Spryker\Glue\MultiCartsRestApi\Dependency\Client\MultiCartsRestApiToCartsRestApiClientInterface
     */
    protected $cartsRestApiClient;

    /**
     * @param \Spryker\Glue\MultiCartsRestApi\Dependency\Client\MultiCartsRestApiToCartsRestApiClientInterface $cartsRestApiClient
     */
    public function __construct(MultiCartsRestApiToCartsRestApiClientInterface $cartsRestApiClient)
    {
        $this->cartsRestApiClient = $cartsRestApiClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(RestRequestInterface $restRequest, QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $restQuoteRequestTransfer = (new RestQuoteRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());

        return $this->cartsRestApiClient->createQuote($restQuoteRequestTransfer);
    }
}
