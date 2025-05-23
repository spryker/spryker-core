<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Reader;

use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Builder\QuoteRequestFilterBuilderInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface;

class QuoteRequestReader implements QuoteRequestReaderInterface
{
    /**
     * @param \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestClient
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface $quoteRequestMapper
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Builder\QuoteRequestFilterBuilderInterface $quoteRequestFilterBuilder
     */
    public function __construct(
        protected QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestClient,
        protected QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder,
        protected QuoteRequestMapperInterface $quoteRequestMapper,
        protected QuoteRequestFilterBuilderInterface $quoteRequestFilterBuilder
    ) {
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getQuoteRequest(RestRequestInterface $restRequest): RestResponseInterface
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer */
        $quoteRequestFilterTransfer = $this->quoteRequestFilterBuilder->buildFilterFromRequest($restRequest);
        $quoteRequestFilterTransfer->setWithVersions(true);

        $quoteRequestResponseTransfer = $this->quoteRequestClient->getQuoteRequest($quoteRequestFilterTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $this->quoteRequestRestResponseBuilder->createQuoteRequestNotFoundErrorResponse();
        }

        return $this->quoteRequestRestResponseBuilder->createQuoteRequestRestResponse(
            $quoteRequestResponseTransfer,
            $restRequest,
            false,
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getQuoteRequestCollection(RestRequestInterface $restRequest): RestResponseInterface
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer */
        $quoteRequestFilterTransfer = $this->quoteRequestFilterBuilder->buildFilterFromRequest($restRequest);
        $quoteRequestFilterTransfer->setWithVersions(true);

        $paginationTransfer = new PaginationTransfer();
        if ($restRequest->getPage() !== null) {
            $paginationTransfer
                ->setMaxPerPage($restRequest->getPage()->getLimit())
                ->setPage(($restRequest->getPage()->getOffset() / $restRequest->getPage()->getLimit()) + 1);

            $quoteRequestFilterTransfer->setPagination($paginationTransfer);
        }

        $quoteRequestCollectionTransfer = $this->quoteRequestClient
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer);

        return $this->quoteRequestRestResponseBuilder->createQuoteRequestCollectionRestResponse(
            $quoteRequestCollectionTransfer,
            $restRequest,
            false,
        );
    }
}
