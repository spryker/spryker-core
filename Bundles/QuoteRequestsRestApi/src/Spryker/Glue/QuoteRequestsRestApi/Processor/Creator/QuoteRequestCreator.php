<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Creator;

use Spryker\Client\QuoteRequestsRestApi\QuoteRequestsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestsRequestMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilderInterface;

class QuoteRequestCreator implements QuoteRequestCreatorInterface
{
    /**
     * @var \Spryker\Client\QuoteRequestsRestApi\QuoteRequestsRestApiClientInterface
     */
    protected $quoteRequestsRestApiClient;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilderInterface
     */
    protected $quoteRequestsRestResponseBuilder;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestsRequestMapperInterface
     */
    protected $quoteRequestsRequestMapper;

    /**
     * @param \Spryker\Client\QuoteRequestsRestApi\QuoteRequestsRestApiClientInterface $quoteRequestsRestApiClient
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilderInterface $quoteRequestsRestResponseBuilder
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestsRequestMapperInterface $quoteRequestsRequestMapper
     */
    public function __construct(
        QuoteRequestsRestApiClientInterface $quoteRequestsRestApiClient,
        QuoteRequestsRestResponseBuilderInterface $quoteRequestsRestResponseBuilder,
        QuoteRequestsRequestMapperInterface $quoteRequestsRequestMapper
    ) {
        $this->quoteRequestsRestApiClient = $quoteRequestsRestApiClient;
        $this->quoteRequestsRestResponseBuilder = $quoteRequestsRestResponseBuilder;
        $this->quoteRequestsRequestMapper = $quoteRequestsRequestMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createQuoteRequest(RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteRequestsRequestTransfer = $this->quoteRequestsRequestMapper
            ->mapRestRequestToQuoteRequestsRequestTransfer($restRequest);

        $quoteRequestResponseTransfer = $this->quoteRequestsRestApiClient->createQuoteRequest($quoteRequestsRequestTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $this->quoteRequestsRestResponseBuilder->createFailedErrorResponse($quoteRequestResponseTransfer->getMessages());
        }

        $quoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequestOrFail();

        return $this->quoteRequestsRestResponseBuilder->createQuoteRequestRestResponse($quoteRequestTransfer);
    }
}
