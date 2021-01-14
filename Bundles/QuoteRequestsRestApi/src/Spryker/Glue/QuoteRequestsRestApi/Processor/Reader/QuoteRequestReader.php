<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Reader;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestsRequestMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilderInterface;

class QuoteRequestReader implements QuoteRequestReaderInterface
{
    protected const PARAM_QUOTE_REQUEST_VERSION_REFERENCE = 'quoteRequestVersionReference';

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface
     */
    private $quoteRequestClient;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilderInterface
     */
    private $quoteRequestsRestResponseBuilder;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestsRequestMapperInterface
     */
    private $quoteRequestsRequestMapper;

    /**
     * @param \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestsRestApiToQuoteRequestClient
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilderInterface $quoteRequestsRestResponseBuilder
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestsRequestMapperInterface $quoteRequestsRequestMapper
     */
    public function __construct(
        QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestsRestApiToQuoteRequestClient,
        QuoteRequestsRestResponseBuilderInterface $quoteRequestsRestResponseBuilder,
        QuoteRequestsRequestMapperInterface $quoteRequestsRequestMapper
    ) {
        $this->quoteRequestClient = $quoteRequestsRestApiToQuoteRequestClient;
        $this->quoteRequestsRestResponseBuilder = $quoteRequestsRestResponseBuilder;
        $this->quoteRequestsRequestMapper = $quoteRequestsRequestMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getQuoteRequest(RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($restRequest->getResource()->getId())
            ->setIdCompanyUser($restRequest->getRestUser()->getIdCompanyUser());

        $quoteRequestResponseTransfer = $this->quoteRequestClient
            ->getQuoteRequest($quoteRequestFilterTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $this->quoteRequestsRestResponseBuilder->createQuoteRequestNotFoundErrorResponse();
        }

        $quoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        return $this->quoteRequestsRestResponseBuilder->createQuoteRequestRestResponse($quoteRequestTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getQuoteRequestCollection(RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setIdCompanyUser($restRequest->getRestUser()->getIdCompanyUser());

        $quoteRequestCollectionTransfer = $this->quoteRequestClient
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer);

        return $this->quoteRequestsRestResponseBuilder->createQuoteRequestCollectionRestResponse($quoteRequestCollectionTransfer);
    }
}
