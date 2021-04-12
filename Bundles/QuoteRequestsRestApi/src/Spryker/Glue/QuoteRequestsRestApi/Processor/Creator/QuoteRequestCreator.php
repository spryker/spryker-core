<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Creator;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToCartsRestApiClientInterface;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestsRequestMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilderInterface;

class QuoteRequestCreator implements QuoteRequestCreatorInterface
{
    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToCartsRestApiClientInterface
     */
    protected $cartsRestApiClient;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface
     */
    protected $quoteRequestClient;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilderInterface
     */
    protected $quoteRequestsRestResponseBuilder;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestsRequestMapperInterface
     */
    protected $quoteRequestsRequestMapper;

    /**
     * @param \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToCartsRestApiClientInterface $cartsRestApiClient
     * @param \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestClient
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilderInterface $quoteRequestsRestResponseBuilder
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestsRequestMapperInterface $quoteRequestsRequestMapper
     */
    public function __construct(
        QuoteRequestsRestApiToCartsRestApiClientInterface $cartsRestApiClient,
        QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestClient,
        QuoteRequestsRestResponseBuilderInterface $quoteRequestsRestResponseBuilder,
        QuoteRequestsRequestMapperInterface $quoteRequestsRequestMapper
    ) {
        $this->cartsRestApiClient = $cartsRestApiClient;
        $this->quoteRequestClient = $quoteRequestClient;
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
        $quoteResponseTransfer = $this->getQuote($restRequest);

        if (!$quoteResponseTransfer->getIsSuccessful() || $quoteResponseTransfer->getQuoteTransfer() === null) {
            return $this->quoteRequestsRestResponseBuilder->createCartNotFoundErrorResponse();
        }

        if (!$quoteResponseTransfer->getQuoteTransfer()->getItems()->count()) {
            return $this->quoteRequestsRestResponseBuilder->createCartIsEmptyErrorResponse();
        }

        $quoteRequestTransfer = $this->quoteRequestsRequestMapper
            ->mapRestRequestToQuoteRequestTransfer($restRequest, $quoteResponseTransfer->getQuoteTransfer());

        $quoteRequestResponseTransfer = $this->quoteRequestClient->createQuoteRequest($quoteRequestTransfer);

        if (
            !$quoteRequestResponseTransfer->getIsSuccessful()
            || $quoteRequestResponseTransfer->getQuoteRequest() === null
        ) {
            return $this->quoteRequestsRestResponseBuilder->createFailedErrorResponse($quoteRequestResponseTransfer->getMessages());
        }

        return $this->quoteRequestsRestResponseBuilder
            ->createQuoteRequestRestResponse($quoteRequestResponseTransfer->getQuoteRequest());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    protected function getQuote(RestRequestInterface $restRequest): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\RestQuoteRequestsRequestAttributesTransfer $restQuoteRequestsRequestAttributesTransfer */
        $restQuoteRequestsRequestAttributesTransfer = $restRequest->getResource()->getAttributes();

        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($restRequest->getRestUser()->getIdCompanyUser());

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier())
            ->setIdCustomer($restRequest->getRestUser()->getSurrogateIdentifier())
            ->setCompanyUserTransfer($companyUserTransfer);

        $quoteTransfer = (new QuoteTransfer())
            ->setUuid($restQuoteRequestsRequestAttributesTransfer->getCartUuid())
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier())
            ->setCustomer($customerTransfer);

        return $this->cartsRestApiClient->findQuoteByUuid($quoteTransfer);
    }
}
