<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Updater;

use Generated\Shared\Transfer\RestQuoteRequestsRequestAttributesTransfer;
use Spryker\Client\QuoteRequestsRestApi\QuoteRequestsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Builder\QuoteRequestFilterBuilderInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Validator\QuoteRequestValidatorInterface;

class QuoteRequestUpdater implements QuoteRequestUpdaterInterface
{
    /**
     * @param \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestClient
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface $quoteRequestMapper
     * @param \Spryker\Client\QuoteRequestsRestApi\QuoteRequestsRestApiClientInterface $quoteRequestsRestApiClient
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Validator\QuoteRequestValidatorInterface $quoteRequestValidator
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Builder\QuoteRequestFilterBuilderInterface $quoteRequestFilterBuilder
     */
    public function __construct(
        protected QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestClient,
        protected QuoteRequestRestResponseBuilderInterface $quoteRequestRestResponseBuilder,
        protected QuoteRequestMapperInterface $quoteRequestMapper,
        protected QuoteRequestsRestApiClientInterface $quoteRequestsRestApiClient,
        protected QuoteRequestValidatorInterface $quoteRequestValidator,
        protected QuoteRequestFilterBuilderInterface $quoteRequestFilterBuilder
    ) {
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
        /** @var \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer */
        $quoteRequestFilterTransfer = $this->quoteRequestFilterBuilder->buildFilterFromRequest($restRequest);
        $quoteRequestFilterTransfer->setWithVersions(true);

        $quoteRequestResponseTransfer = $this->quoteRequestClient->getQuoteRequest($quoteRequestFilterTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $this->quoteRequestRestResponseBuilder->createQuoteRequestNotFoundErrorResponse();
        }

        $quoteRequestTransfer = $this->quoteRequestMapper
            ->mapRestQuoteRequestsRequestAttributesTransferToQuoteRequestTransfer(
                $restQuoteRequestsRequestAttributesTransfer,
                $quoteRequestResponseTransfer->getQuoteRequestOrFail(),
            );

        if (!$this->quoteRequestValidator->validateDeliveryDate($quoteRequestTransfer)) {
            return $this->quoteRequestRestResponseBuilder->createDeliveryDateIsNotValidErrorResponse();
        }

        $quoteRequestResponseTransfer = $this->quoteRequestsRestApiClient->updateQuoteRequest($quoteRequestTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $this->quoteRequestRestResponseBuilder->createFailedErrorResponse($quoteRequestResponseTransfer);
        }

        return $this->quoteRequestRestResponseBuilder->createQuoteRequestRestResponse(
            $quoteRequestResponseTransfer,
            $restRequest,
        );
    }
}
