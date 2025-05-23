<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestAgentsRestApi\Processor\Canceller;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToQuoteRequestAgentClientInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\RestResource\QuoteRequestAgentsRestApiToQuoteRequestsRestApiResourceInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface;
use Spryker\Glue\QuoteRequestAgentsRestApi\QuoteRequestAgentsRestApiConfig;

class QuoteRequestCanceller implements QuoteRequestCancellerInterface
{
    /**
     * @param \Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\Client\QuoteRequestAgentsRestApiToQuoteRequestAgentClientInterface $quoteRequestAgentClient
     * @param \Spryker\Glue\QuoteRequestAgentsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder
     * @param \Spryker\Glue\QuoteRequestAgentsRestApi\Dependency\RestResource\QuoteRequestAgentsRestApiToQuoteRequestsRestApiResourceInterface $quoteRequestsRestApiResource
     */
    public function __construct(
        protected QuoteRequestAgentsRestApiToQuoteRequestAgentClientInterface $quoteRequestAgentClient,
        protected QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder,
        protected QuoteRequestAgentsRestApiToQuoteRequestsRestApiResourceInterface $quoteRequestsRestApiResource
    ) {
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function cancelQuoteRequest(RestRequestInterface $restRequest): RestResponseInterface
    {
        $parentResource = $restRequest->findParentResourceByType(QuoteRequestAgentsRestApiConfig::RESOURCE_AGENT_QUOTE_REQUESTS);
        if ($parentResource === null || $parentResource->getId() === null) {
            return $this->quoteRequestRestResponseBuilder->createQuoteRequestReferenceMissingErrorResponse();
        }

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($parentResource->getId())
            ->setWithVersions(true);

        $quoteRequestResponseTransfer = $this->quoteRequestAgentClient->cancelQuoteRequest($quoteRequestFilterTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $this->quoteRequestRestResponseBuilder->createFailedErrorResponse($quoteRequestResponseTransfer);
        }

        return $this->quoteRequestsRestApiResource->createQuoteRequestRestResponse(
            $quoteRequestResponseTransfer,
            $restRequest,
        );
    }
}
