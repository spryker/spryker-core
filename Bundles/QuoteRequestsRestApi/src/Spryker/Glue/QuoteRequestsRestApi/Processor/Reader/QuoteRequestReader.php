<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Reader;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface;

class QuoteRequestReader implements QuoteRequestReaderInterface
{
    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface
     */
    protected $quoteRequestRestResponseBuilder;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface
     */
    protected $quoteRequestMapper;

    /**
     * @param \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestsRestApiToQuoteRequestClient
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface $quoteRequestMapper
     */
    public function __construct(
        QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestsRestApiToQuoteRequestClient,
        QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder,
        QuoteRequestMapperInterface $quoteRequestMapper
    ) {
        $this->quoteRequestClient = $quoteRequestsRestApiToQuoteRequestClient;
        $this->quoteRequestRestResponseBuilder = $quoteRequestRestResponseBuilder;
        $this->quoteRequestMapper = $quoteRequestMapper;
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
            ->setWithVersions(true);

        $restUserTransfer = $restRequest->getRestUser();
        if ($restUserTransfer) {
            $companyUserTransfer = (new CompanyUserTransfer())
                ->setIdCompanyUser($restUserTransfer->getIdCompanyUser());

            $quoteRequestFilterTransfer->setCompanyUser($companyUserTransfer);
        }

        $quoteRequestResponseTransfer = $this->quoteRequestClient
            ->getQuoteRequest($quoteRequestFilterTransfer);

        if (
            !$quoteRequestResponseTransfer->getIsSuccessful()
            || $quoteRequestResponseTransfer->getQuoteRequest() === null
        ) {
            return $this->quoteRequestRestResponseBuilder->createQuoteRequestNotFoundErrorResponse();
        }

        return $this->quoteRequestRestResponseBuilder
            ->createQuoteRequestRestResponse(
                $quoteRequestResponseTransfer,
                $restRequest->getMetadata()->getLocale()
            );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getQuoteRequestCollection(RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteRequestFilterTransfer = new QuoteRequestFilterTransfer();

        $restUserTransfer = $restRequest->getRestUser();
        if ($restUserTransfer) {
            $companyUserTransfer = (new CompanyUserTransfer())
                ->setIdCompanyUser($restUserTransfer->getIdCompanyUser());

            $quoteRequestFilterTransfer->setCompanyUser($companyUserTransfer);
        }

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
            $restRequest->getMetadata()->getLocale()
        );
    }
}
