<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Updater;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsRequestAttributesTransfer;
use Spryker\Client\QuoteRequestsRestApi\QuoteRequestsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Validator\QuoteRequestValidatorInterface;

class QuoteRequestUpdater implements QuoteRequestUpdaterInterface
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
     * @var \Spryker\Client\QuoteRequestsRestApi\QuoteRequestsRestApiClientInterface
     */
    protected $quoteRequestsRestApiClient;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Processor\Validator\QuoteRequestValidatorInterface
     */
    protected $quoteRequestValidator;

    /**
     * @param \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestsRestApiToQuoteRequestClient
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface $quoteRequestMapper
     * @param \Spryker\Client\QuoteRequestsRestApi\QuoteRequestsRestApiClientInterface $quoteRequestsRestApiClient
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Validator\QuoteRequestValidatorInterface $quoteRequestValidator
     */
    public function __construct(
        QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestsRestApiToQuoteRequestClient,
        QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder,
        QuoteRequestMapperInterface $quoteRequestMapper,
        QuoteRequestsRestApiClientInterface $quoteRequestsRestApiClient,
        QuoteRequestValidatorInterface $quoteRequestValidator
    ) {
        $this->quoteRequestClient = $quoteRequestsRestApiToQuoteRequestClient;
        $this->quoteRequestRestResponseBuilder = $quoteRequestRestResponseBuilder;
        $this->quoteRequestMapper = $quoteRequestMapper;
        $this->quoteRequestsRestApiClient = $quoteRequestsRestApiClient;
        $this->quoteRequestValidator = $quoteRequestValidator;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestQuoteRequestsRequestAttributesTransfer $restQuoteRequestsRequestAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function update(
        RestRequestInterface $restRequest,
        RestQuoteRequestsRequestAttributesTransfer $restQuoteRequestsRequestAttributesTransfer
    ): RestResponseInterface {
        $quoteRequestResponseTransfer = $this->getQuoteRequest($restRequest);
        if (
            !$quoteRequestResponseTransfer->getIsSuccessful()
            || $quoteRequestResponseTransfer->getQuoteRequest() === null
        ) {
            return $this->quoteRequestRestResponseBuilder->createQuoteRequestNotFoundErrorResponse();
        }

        $quoteRequestTransfer = $this->quoteRequestMapper
            ->mapRestQuoteRequestsRequestAttributesTransferToQuoteRequestTransfer(
                $restQuoteRequestsRequestAttributesTransfer,
                $quoteRequestResponseTransfer->getQuoteRequest(),
            );

        if (!$this->quoteRequestValidator->validateDeliveryDate($quoteRequestTransfer)) {
            return $this->quoteRequestRestResponseBuilder->createDeliveryDateIsNotValidErrorResponse();
        }

        $quoteRequestResponseTransfer = $this->quoteRequestsRestApiClient->updateQuoteRequest($quoteRequestTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $this->quoteRequestRestResponseBuilder->createFailedErrorResponse($quoteRequestResponseTransfer->getMessages()->getArrayCopy());
        }

        return $this->quoteRequestRestResponseBuilder
            ->createQuoteRequestRestResponse(
                $quoteRequestResponseTransfer,
                $restRequest->getMetadata()->getLocale(),
            );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function getQuoteRequest(RestRequestInterface $restRequest): QuoteRequestResponseTransfer
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

        return $this->quoteRequestClient
            ->getQuoteRequest($quoteRequestFilterTransfer);
    }
}
