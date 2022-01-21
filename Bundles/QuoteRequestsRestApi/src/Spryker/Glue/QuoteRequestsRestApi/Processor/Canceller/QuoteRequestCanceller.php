<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Canceller;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface;
use Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiConfig;

class QuoteRequestCanceller implements QuoteRequestCancellerInterface
{
    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface
     */
    protected $quoteRequestRestResponseBuilder;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @param \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestClient
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder
     */
    public function __construct(
        QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestClient,
        QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder
    ) {
        $this->quoteRequestClient = $quoteRequestClient;
        $this->quoteRequestRestResponseBuilder = $quoteRequestRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function cancelQuoteRequest(RestRequestInterface $restRequest): RestResponseInterface
    {
        $parentResource = $restRequest->findParentResourceByType(QuoteRequestsRestApiConfig::RESOURCE_QUOTE_REQUESTS);
        if (!$parentResource || $parentResource->getId() === null) {
            return $this->quoteRequestRestResponseBuilder->createQuoteRequestReferenceMissingErrorResponse();
        }

        $quoteRequestFilterTransfer = $this->createQuoteRequestFilterTransfer();
        $quoteRequestFilterTransfer->setQuoteRequestReference($parentResource->getId());

        $restUserTransfer = $restRequest->getRestUser();
        if ($restUserTransfer) {
            $companyUserTransfer = (new CompanyUserTransfer())
                ->setIdCompanyUser($restUserTransfer->getIdCompanyUser());

            $quoteRequestFilterTransfer
                ->setCompanyUser($companyUserTransfer)
                ->setIdCompanyUser($restUserTransfer->getIdCompanyUser());
        }

        $quoteRequestResponseTransfer = $this->quoteRequestClient->cancelQuoteRequest($quoteRequestFilterTransfer);

        if ($quoteRequestResponseTransfer->getIsSuccessful()) {
            return $this->quoteRequestRestResponseBuilder->createNoContentResponse();
        }

        if ($quoteRequestResponseTransfer->getQuoteRequest() === null) {
            return $this->quoteRequestRestResponseBuilder->createQuoteRequestNotFoundErrorResponse();
        }

        return $this->quoteRequestRestResponseBuilder->createFailedErrorResponse(($quoteRequestResponseTransfer->getMessages())->getArrayCopy());
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestFilterTransfer
     */
    protected function createQuoteRequestFilterTransfer(): QuoteRequestFilterTransfer
    {
        return new QuoteRequestFilterTransfer();
    }
}
