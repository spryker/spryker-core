<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Creator;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Client\QuoteRequestsRestApi\QuoteRequestsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Validator\QuoteRequestValidatorInterface;

class QuoteRequestCreator implements QuoteRequestCreatorInterface
{
    /**
     * @var \Spryker\Client\QuoteRequestsRestApi\QuoteRequestsRestApiClientInterface
     */
    protected $quoteRequestsRestApiClient;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface
     */
    protected $quoteRequestRestResponseBuilder;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface
     */
    protected $quoteRequestMapper;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Processor\Validator\QuoteRequestValidatorInterface
     */
    protected $quoteRequestValidator;

    /**
     * @param \Spryker\Client\QuoteRequestsRestApi\QuoteRequestsRestApiClientInterface $quoteRequestsRestApiClient
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface $quoteRequestMapper
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Validator\QuoteRequestValidatorInterface $quoteRequestValidator
     */
    public function __construct(
        QuoteRequestsRestApiClientInterface $quoteRequestsRestApiClient,
        QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder,
        QuoteRequestMapperInterface $quoteRequestMapper,
        QuoteRequestValidatorInterface $quoteRequestValidator
    ) {
        $this->quoteRequestsRestApiClient = $quoteRequestsRestApiClient;
        $this->quoteRequestRestResponseBuilder = $quoteRequestRestResponseBuilder;
        $this->quoteRequestMapper = $quoteRequestMapper;
        $this->quoteRequestValidator = $quoteRequestValidator;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createQuoteRequest(RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteRequestTransfer = $this->quoteRequestMapper
            ->mapRestRequestToQuoteRequestTransfer($restRequest, new QuoteRequestTransfer());

        if (!$this->quoteRequestValidator->validateDeliveryDate($quoteRequestTransfer)) {
            return $this->quoteRequestRestResponseBuilder->createDeliveryDateIsNotValidErrorResponse();
        }

        $quoteRequestResponseTransfer = $this->quoteRequestsRestApiClient->createQuoteRequest($quoteRequestTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $this->quoteRequestRestResponseBuilder->createFailedErrorResponse($quoteRequestResponseTransfer->getMessages()->getArrayCopy());
        }

        return $this->quoteRequestRestResponseBuilder
            ->createQuoteRequestRestResponse(
                $quoteRequestResponseTransfer,
                $restRequest->getMetadata()->getLocale(),
            );
    }
}
