<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Converter;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\RestResource\QuoteRequestsRestApiToCartsRestApiResourceInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Builder\QuoteRequestFilterBuilderInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface;

class QuoteRequestConverter implements QuoteRequestConverterInterface
{
    /**
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder
     * @param \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestClient
     * @param \Spryker\Glue\QuoteRequestsRestApi\Dependency\RestResource\QuoteRequestsRestApiToCartsRestApiResourceInterface $cartsRestApiResource
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Builder\QuoteRequestFilterBuilderInterface $quoteRequestFilterBuilder
     */
    public function __construct(
        protected QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder,
        protected QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestClient,
        protected QuoteRequestsRestApiToCartsRestApiResourceInterface $cartsRestApiResource,
        protected QuoteRequestFilterBuilderInterface $quoteRequestFilterBuilder
    ) {
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function convertQuoteRequestToCart(RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteRequestFilterTransfer = $this->quoteRequestFilterBuilder->buildFilterFromRequest($restRequest, true);
        if (!$quoteRequestFilterTransfer) {
            return $this->quoteRequestRestResponseBuilder->createQuoteRequestReferenceMissingErrorResponse();
        }

        $quoteRequestTransfer = $this->quoteRequestClient
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer)
            ->getQuoteRequests()
            ->getIterator()
            ->current();

        if (!$quoteRequestTransfer) {
            return $this->quoteRequestRestResponseBuilder->createQuoteRequestNotFoundErrorResponse();
        }

        $quoteResponseTransfer = $this->quoteRequestClient->convertQuoteRequestToLockedQuote($quoteRequestTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            return $this->cartsRestApiResource->createCartRestResponse(
                $quoteResponseTransfer->getQuoteTransferOrFail(),
                $restRequest,
            );
        }

        return $this->quoteRequestRestResponseBuilder->createFailedQuoteErrorResponse($quoteResponseTransfer);
    }
}
