<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class QuoteRequestRestResponseBuilder implements QuoteRequestRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface
     */
    protected $quoteRequestMapper;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiConfig
     */
    protected $quoteRequestsRestApiConfig;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface $quoteRequestMapper
     * @param \Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiConfig $quoteRequestsRestApiConfig
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        QuoteRequestMapperInterface $quoteRequestMapper,
        QuoteRequestsRestApiConfig $quoteRequestsRestApiConfig
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->quoteRequestMapper = $quoteRequestMapper;
        $this->quoteRequestsRestApiConfig = $quoteRequestsRestApiConfig;
    }

    /**
     * @param array<\Generated\Shared\Transfer\MessageTransfer> $messageTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createFailedErrorResponse(array $messageTransfers): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($messageTransfers as $messageTransfer) {
            $restErrorMessageTransfer = $this->mapMessageTransferToRestErrorMessageTransfer(
                $messageTransfer,
                new RestErrorMessageTransfer()
            );

            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createQuoteRequestRestResponse(
        QuoteRequestResponseTransfer $quoteRequestResponseTransfer,
        string $localeName
    ): RestResponseInterface {
        $quoteRequestTransfers = [$quoteRequestResponseTransfer->getQuoteRequestOrFail()];
        $restQuoteRequestsAttributesTransfers = $this->quoteRequestMapper
            ->mapQuoteRequestTransfersToRestQuoteRequestsAttributesTransfers(
                $quoteRequestTransfers,
                [],
                $localeName
            );

        return $this->buildRestResponse(
            $quoteRequestTransfers,
            $restQuoteRequestsAttributesTransfers
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestCollectionTransfer $quoteRequestCollectionTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createQuoteRequestCollectionRestResponse(
        QuoteRequestCollectionTransfer $quoteRequestCollectionTransfer,
        string $localeName
    ): RestResponseInterface {
        $quoteRequestTransfers = ($quoteRequestCollectionTransfer->getQuoteRequests())->getArrayCopy();
        $restQuoteRequestsAttributesTransfers = $this->quoteRequestMapper
            ->mapQuoteRequestTransfersToRestQuoteRequestsAttributesTransfers(
                $quoteRequestTransfers,
                [],
                $localeName
            );

        return $this->buildRestResponse(
            $quoteRequestTransfers,
            $restQuoteRequestsAttributesTransfers
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
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createNoContentResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder
            ->createRestResponse()
            ->setStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function mapMessageTransferToRestErrorMessageTransfer(MessageTransfer $messageTransfer, RestErrorMessageTransfer $restErrorMessageTransfer)
    {
        $errorIdentifier = $messageTransfer->getValue();

        $errorIdentifierToRestErrorMapping = $this->quoteRequestsRestApiConfig->getErrorIdentifierToRestErrorMapping();
        if ($errorIdentifier && isset($errorIdentifierToRestErrorMapping[$errorIdentifier])) {
            return $restErrorMessageTransfer->fromArray(
                $errorIdentifierToRestErrorMapping[$errorIdentifier],
                true
            );
        }

        return $this->createErrorMessageTransfer($messageTransfer);
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
                    $restQuoteRequestsAttributesTransfer
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
            $restQuoteRequestsAttributesTransfer
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
}
