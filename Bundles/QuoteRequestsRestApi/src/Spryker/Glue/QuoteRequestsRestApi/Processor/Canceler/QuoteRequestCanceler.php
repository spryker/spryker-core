<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Canceler;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilderInterface;
use Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiConfig;

class QuoteRequestCanceler implements QuoteRequestCancelerInterface
{
    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilderInterface
     */
    protected $quoteRequestsRestResponseBuilder;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @param \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestClient
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilderInterface $quoteRequestsRestResponseBuilder
     */
    public function __construct(
        QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestClient,
        QuoteRequestsRestResponseBuilderInterface $quoteRequestsRestResponseBuilder
    ) {
        $this->quoteRequestClient = $quoteRequestClient;
        $this->quoteRequestsRestResponseBuilder = $quoteRequestsRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function cancelQuoteRequest(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (
            $restRequest->findParentResourceByType(QuoteRequestsRestApiConfig::RESOURCE_QUOTE_REQUESTS) === null
            || $restRequest->findParentResourceByType(QuoteRequestsRestApiConfig::RESOURCE_QUOTE_REQUESTS)->getId() === null
        ) {
            return $this->quoteRequestsRestResponseBuilder->createQuoteRequestReferenceMissingErrorResponse();
        }

        $quoteRequestReference = $restRequest
            ->findParentResourceByType(QuoteRequestsRestApiConfig::RESOURCE_QUOTE_REQUESTS)
            ->getId();

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setIdCompanyUser($restRequest->getRestUser()->getIdCompanyUserOrFail())
            ->setQuoteRequestReference($quoteRequestReference);

        $quoteRequestResponseTransfer = $this->quoteRequestClient->cancelQuoteRequest($quoteRequestFilterTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $this->quoteRequestsRestResponseBuilder->createFailedErrorResponse($quoteRequestResponseTransfer->getMessages());
        }

        return $this->quoteRequestsRestResponseBuilder->createNoContentResponse();
    }
}
