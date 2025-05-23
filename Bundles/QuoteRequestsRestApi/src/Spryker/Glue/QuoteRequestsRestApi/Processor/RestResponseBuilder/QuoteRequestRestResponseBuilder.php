<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class QuoteRequestRestResponseBuilder implements QuoteRequestRestResponseBuilderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface $quoteRequestMapper
     * @param \Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiConfig $quoteRequestsRestApiConfig
     */
    public function __construct(
        protected RestResourceBuilderInterface $restResourceBuilder,
        protected QuoteRequestMapperInterface $quoteRequestMapper,
        protected QuoteRequestsRestApiConfig $quoteRequestsRestApiConfig
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createFailedErrorResponse(QuoteRequestResponseTransfer $quoteRequestResponseTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        foreach ($quoteRequestResponseTransfer->getMessages() as $messageTransfer) {
            $restErrorMessageTransfer = $this->mapMessageTransferToRestErrorMessageTransfer(
                $messageTransfer,
                new RestErrorMessageTransfer(),
            );

            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createFailedQuoteErrorResponse(QuoteResponseTransfer $quoteResponseTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
            $restErrorMessageTransfer = $this->mapQuoteErrorTransferToRestErrorMessageTransfer(
                $quoteErrorTransfer,
                new RestErrorMessageTransfer(),
            );

            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param bool $isLatestVersionVisible
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createQuoteRequestRestResponse(
        QuoteRequestResponseTransfer $quoteRequestResponseTransfer,
        RestRequestInterface $restRequest,
        bool $isLatestVersionVisible = true
    ): RestResponseInterface {
        $quoteRequestTransfers = [$quoteRequestResponseTransfer->getQuoteRequestOrFail()];
        $restQuoteRequestsAttributesTransfers = $this->quoteRequestMapper
            ->mapQuoteRequestTransfersToRestQuoteRequestsAttributesTransfers(
                $quoteRequestTransfers,
                [],
                $restRequest,
                $isLatestVersionVisible,
            );

        return $this->buildRestResponse(
            $quoteRequestTransfers,
            $restQuoteRequestsAttributesTransfers,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCollectionTransfer $quoteRequestCollectionTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param bool $isLatestVersionVisible
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createQuoteRequestCollectionRestResponse(
        QuoteRequestCollectionTransfer $quoteRequestCollectionTransfer,
        RestRequestInterface $restRequest,
        bool $isLatestVersionVisible = true
    ): RestResponseInterface {
        $quoteRequestTransfers = ($quoteRequestCollectionTransfer->getQuoteRequests())->getArrayCopy();
        $restQuoteRequestsAttributesTransfers = $this->quoteRequestMapper
            ->mapQuoteRequestTransfersToRestQuoteRequestsAttributesTransfers(
                $quoteRequestTransfers,
                [],
                $restRequest,
                $isLatestVersionVisible,
            );

        return $this->buildRestResponse(
            $quoteRequestTransfers,
            $restQuoteRequestsAttributesTransfers,
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createQuoteRequestNotFoundErrorResponse(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(QuoteRequestsRestApiConfig::RESPONSE_CODE_QUOTE_REQUEST_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(QuoteRequestsRestApiConfig::RESPONSE_DETAIL_QUOTE_REQUEST_NOT_FOUND);

        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError($restErrorTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createDeliveryDateIsNotValidErrorResponse(): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(QuoteRequestsRestApiConfig::RESPONSE_CODE_METADATA_DELIVERY_DATE_IS_INVALID)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(QuoteRequestsRestApiConfig::RESPONSE_DETAILS_METADATA_DELIVERY_DATE_IS_INVALID);

        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError($restErrorTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createQuoteRequestReferenceMissingErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(QuoteRequestsRestApiConfig::RESPONSE_CODE_QUOTE_REQUEST_REFERENCE_MISSING)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(QuoteRequestsRestApiConfig::RESPONSE_DETAIL_QUOTE_REQUEST_REFERENCE_MISSING);

        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError($restErrorMessageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function mapMessageTransferToRestErrorMessageTransfer(
        MessageTransfer $messageTransfer,
        RestErrorMessageTransfer $restErrorMessageTransfer
    ): RestErrorMessageTransfer {
        $errorIdentifier = $messageTransfer->getValue();

        $errorIdentifierToRestErrorMapping = $this->quoteRequestsRestApiConfig->getErrorIdentifierToRestErrorMapping();
        if ($errorIdentifier && isset($errorIdentifierToRestErrorMapping[$errorIdentifier])) {
            return $restErrorMessageTransfer->fromArray(
                $errorIdentifierToRestErrorMapping[$errorIdentifier],
                true,
            );
        }

        return $this->createErrorMessageTransfer($messageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteErrorTransfer $quoteErrorTransfer
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function mapQuoteErrorTransferToRestErrorMessageTransfer(
        QuoteErrorTransfer $quoteErrorTransfer,
        RestErrorMessageTransfer $restErrorMessageTransfer
    ): RestErrorMessageTransfer {
        $errorIdentifier = $quoteErrorTransfer->getMessage();

        $errorIdentifierToRestErrorMapping = $this->quoteRequestsRestApiConfig->getErrorIdentifierToRestErrorMapping();
        if ($errorIdentifier && isset($errorIdentifierToRestErrorMapping[$errorIdentifier])) {
            return $restErrorMessageTransfer->fromArray(
                $errorIdentifierToRestErrorMapping[$errorIdentifier],
                true,
            );
        }

        return $this->createQuoteErrorMessageTransfer($quoteErrorTransfer);
    }

    /**
     * @param array<\Generated\Shared\Transfer\QuoteRequestTransfer> $quoteRequestTransfers
     * @param array<\Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer> $restQuoteRequestsAttributesTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function buildRestResponse(
        array $quoteRequestTransfers,
        array $restQuoteRequestsAttributesTransfers
    ): RestResponseInterface {
        $totalItems = 0;
        $limit = 0;

        $restResponse = $this->restResourceBuilder->createRestResponse($totalItems, $limit);

        $indexedRestQuoteRequestsAttributesTransfers = [];
        foreach ($restQuoteRequestsAttributesTransfers as $restQuoteRequestsAttributesTransfer) {
            $indexedRestQuoteRequestsAttributesTransfers[$restQuoteRequestsAttributesTransfer->getQuoteRequestReference()] = $restQuoteRequestsAttributesTransfer;
        }

        foreach ($quoteRequestTransfers as $quoteRequestTransfer) {
            if (isset($indexedRestQuoteRequestsAttributesTransfers[$quoteRequestTransfer->getQuoteRequestReference()])) {
                $restQuoteRequestsAttributesTransfer = $indexedRestQuoteRequestsAttributesTransfers[$quoteRequestTransfer->getQuoteRequestReference()];
                $restResponse->addResource($this->createRestResource(
                    $quoteRequestTransfer,
                    $restQuoteRequestsAttributesTransfer,
                ));
            }
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createRestResource(
        QuoteRequestTransfer $quoteRequestTransfer,
        RestQuoteRequestsAttributesTransfer $restQuoteRequestsAttributesTransfer
    ): RestResourceInterface {
        $quoteRequestResource = $this->restResourceBuilder->createRestResource(
            QuoteRequestsRestApiConfig::RESOURCE_QUOTE_REQUESTS,
            $restQuoteRequestsAttributesTransfer->getQuoteRequestReference(),
            $restQuoteRequestsAttributesTransfer,
        );

        return $quoteRequestResource->setPayload($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createErrorMessageTransfer(MessageTransfer $messageTransfer): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(QuoteRequestsRestApiConfig::RESPONSE_CODE_QUOTE_REQUEST_VALIDATION)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail($messageTransfer->getMessage() ?? QuoteRequestsRestApiConfig::RESPONSE_PROBLEM_CREATING_QUOTE_REQUEST_DESCRIPTION);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteErrorTransfer $quoteErrorTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createQuoteErrorMessageTransfer(QuoteErrorTransfer $quoteErrorTransfer): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(QuoteRequestsRestApiConfig::RESPONSE_CODE_QUOTE_REQUEST_VALIDATION)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail($quoteErrorTransfer->getMessage() ?? QuoteRequestsRestApiConfig::RESPONSE_PROBLEM_CREATING_QUOTE_REQUEST_DESCRIPTION);
    }
}
