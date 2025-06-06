<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Sender;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Builder\QuoteRequestFilterBuilderInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface;

class QuoteRequestSender implements QuoteRequestSenderInterface
{
    /**
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder
     * @param \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestClient
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Builder\QuoteRequestFilterBuilderInterface $quoteRequestFilterBuilder
     */
    public function __construct(
        protected QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder,
        protected QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestClient,
        protected QuoteRequestFilterBuilderInterface $quoteRequestFilterBuilder
    ) {
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function sendQuoteRequestToUser(RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteRequestFilterTransfer = $this->quoteRequestFilterBuilder->buildFilterFromRequest($restRequest, true);
        if (!$quoteRequestFilterTransfer) {
            return $this->quoteRequestRestResponseBuilder->createQuoteRequestReferenceMissingErrorResponse();
        }

        $quoteRequestFilterTransfer->setWithVersions(true);
        $quoteRequestResponseTransfer = $this->quoteRequestClient->sendQuoteRequestToUser($quoteRequestFilterTransfer);

        if ($quoteRequestResponseTransfer->getIsSuccessful()) {
            return $this->quoteRequestRestResponseBuilder->createQuoteRequestRestResponse(
                $quoteRequestResponseTransfer,
                $restRequest,
            );
        }

        return $this->quoteRequestRestResponseBuilder->createFailedErrorResponse($quoteRequestResponseTransfer);
    }
}
