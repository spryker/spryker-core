<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestAgentsRestApi\Processor\Sender;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToQuoteRequestAgentClientInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\QuoteRequestAgentsRestApiConfig;

class QuoteRequestSender implements QuoteRequestSenderInterface
{
    /**
     * @var \Spryker\Glue\QuoteRequestAgentsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface
     */
    protected $quoteRequestRestResponseBuilder;

    /**
     * @var \Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToQuoteRequestAgentClientInterface
     */
    protected $quoteRequestAgentClient;

    /**
     * @param \Spryker\Glue\QuoteRequestAgentsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder
     * @param \Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToQuoteRequestAgentClientInterface $quoteRequestAgentClient
     */
    public function __construct(
        QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder,
        QuoteRequestAgentsRestApiToQuoteRequestAgentClientInterface $quoteRequestAgentClient
    ) {
        $this->quoteRequestRestResponseBuilder = $quoteRequestRestResponseBuilder;
        $this->quoteRequestAgentClient = $quoteRequestAgentClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function sendQuoteRequest(RestRequestInterface $restRequest): RestResponseInterface
    {
        $parentResource = $restRequest->findParentResourceByType(QuoteRequestAgentsRestApiConfig::RESOURCE_AGENT_QUOTE_REQUESTS);
        if (!$parentResource || $parentResource->getId() === null) {
            return $this->quoteRequestRestResponseBuilder->createQuoteRequestReferenceMissingErrorResponse();
        }

        $quoteRequestFilterTransfer = $this->createQuoteRequestFilterTransfer($parentResource);

        $quoteRequestResponseTransfer = $this->quoteRequestAgentClient->sendQuoteRequestToCustomer($quoteRequestFilterTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $this->quoteRequestRestResponseBuilder->createFailedErrorResponse(($quoteRequestResponseTransfer->getMessages()));
        }

        return $this->quoteRequestRestResponseBuilder->createNoContentResponse();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $parentResource
     *
     * @return \Generated\Shared\Transfer\QuoteRequestFilterTransfer
     */
    protected function createQuoteRequestFilterTransfer(RestResourceInterface $parentResource): QuoteRequestFilterTransfer
    {
        return (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($parentResource->getId());
    }
}
