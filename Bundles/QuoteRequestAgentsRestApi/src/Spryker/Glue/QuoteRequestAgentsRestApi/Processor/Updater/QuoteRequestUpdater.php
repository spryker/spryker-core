<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestAgentsRestApi\Processor\Updater;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToQuoteRequestAgentClientInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\RestResource\QuoteRequestAgentsRestApiToQuoteRequestsRestApiResourceInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\Processor\Mapper\QuoteRequestMapperInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\Processor\Validator\QuoteRequestValidatorInterface;

class QuoteRequestUpdater implements QuoteRequestUpdaterInterface
{
    /**
     * @param \Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToQuoteRequestAgentClientInterface $quoteRequestAgentClient
     * @param \Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\RestResource\QuoteRequestAgentsRestApiToQuoteRequestsRestApiResourceInterface $quoteRequestsRestApiResource
     * @param \Spryker\Glue\QuoteRequestAgentsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder
     * @param \Spryker\Glue\QuoteRequestAgentsRestApi\Processor\Mapper\QuoteRequestMapperInterface $quoteRequestMapper
     * @param \Spryker\Glue\QuoteRequestAgentsRestApi\Processor\Validator\QuoteRequestValidatorInterface $quoteRequestValidator
     */
    public function __construct(
        protected QuoteRequestAgentsRestApiToQuoteRequestAgentClientInterface $quoteRequestAgentClient,
        protected QuoteRequestAgentsRestApiToQuoteRequestsRestApiResourceInterface $quoteRequestsRestApiResource,
        protected QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder,
        protected QuoteRequestMapperInterface $quoteRequestMapper,
        protected QuoteRequestValidatorInterface $quoteRequestValidator
    ) {
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function update(RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteRequestTransfer = $this->getQuoteRequest($restRequest);
        if ($quoteRequestTransfer === null) {
            return $this->quoteRequestRestResponseBuilder->createQuoteRequestNotFoundErrorResponse();
        }
        /** @var \Generated\Shared\Transfer\RestAgentQuoteRequestsRequestAttributesTransfer $quoteRequestAgentsRequestAttributesTransfer */
        $quoteRequestAgentsRequestAttributesTransfer = $restRequest->getResource()->getAttributes();

        $quoteRequestTransfer = $this->quoteRequestMapper->mapRestAgentQuoteRequestsRequestAttributesTransferToQuoteRequestTransfer(
            $quoteRequestAgentsRequestAttributesTransfer,
            $quoteRequestTransfer,
        );

        if (!$this->quoteRequestValidator->validateDeliveryDate($quoteRequestTransfer)) {
            return $this->quoteRequestRestResponseBuilder->createDeliveryDateIsNotValidErrorResponse();
        }

        $quoteRequestResponseTransfer = $this->quoteRequestAgentClient->updateQuoteRequest($quoteRequestTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $this->quoteRequestRestResponseBuilder->createFailedErrorResponse($quoteRequestResponseTransfer);
        }

        return $this->quoteRequestsRestApiResource->createQuoteRequestRestResponse(
            $quoteRequestResponseTransfer,
            $restRequest,
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    protected function getQuoteRequest(RestRequestInterface $restRequest): ?QuoteRequestTransfer
    {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($restRequest->getResource()->getId())
            ->setWithVersions(true);

        return $this->quoteRequestAgentClient
            ->findQuoteRequest($quoteRequestFilterTransfer);
    }
}
