<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class CartRestResponseBuilder implements CartRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\CartsRestApiConfig
     */
    protected $config;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\CartsRestApi\CartsRestApiConfig $config
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CartsRestApi\Dependency\Client\CartsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        CartsRestApiConfig $config,
        RestResourceBuilderInterface $restResourceBuilder,
        CartsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
        $this->config = $config;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $cartRestResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartRestResponse(RestResourceInterface $cartRestResource): RestResponseInterface
    {
        return $this->createRestResponse()->addResource($cartRestResource);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteErrorTransfer[]|\ArrayObject $errors
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createFailedErrorResponse(ArrayObject $errors, string $localeName): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($errors as $quoteErrorTransfer) {
            $restResponse->addError(
                $this->mapQuoteErrorTransferToRestErrorMessageTransfer(
                    $quoteErrorTransfer,
                    new RestErrorMessageTransfer(),
                    $localeName
                )
            );
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteErrorTransfer $quoteErrorTransfer
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function mapQuoteErrorTransferToRestErrorMessageTransfer(
        QuoteErrorTransfer $quoteErrorTransfer,
        RestErrorMessageTransfer $restErrorMessageTransfer,
        string $localeName
    ): RestErrorMessageTransfer {
        if ($quoteErrorTransfer->getErrorIdentifier()) {
            $errorIdentifierMapping = $this->config->getErrorIdentifierToRestErrorMapping()[$quoteErrorTransfer->getErrorIdentifier()];
            $restErrorMessageTransfer->fromArray($errorIdentifierMapping, true);
        }

        if ($quoteErrorTransfer->getMessage()) {
            return $this->createErrorMessageTransfer($quoteErrorTransfer, $localeName);
        }

        return $restErrorMessageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteErrorTransfer $quoteErrorTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createErrorMessageTransfer(QuoteErrorTransfer $quoteErrorTransfer, string $localeName): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(CartsRestApiConfig::RESPONSE_CODE_ITEM_VALIDATION)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail($this->glossaryStorageClient->translate($quoteErrorTransfer->getMessage(), $localeName));
    }
}
