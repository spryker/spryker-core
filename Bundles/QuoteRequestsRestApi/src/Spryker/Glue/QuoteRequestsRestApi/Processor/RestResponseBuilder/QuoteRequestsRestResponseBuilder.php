<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestQuoteRequestsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\ErrorMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\QuoteRequestsRestApiConfig;

class QuoteRequestsRestResponseBuilder implements QuoteRequestsRestResponseBuilderInterface
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
     * @var \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\ErrorMapperInterface
     */
    protected $errorMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestMapperInterface $quoteRequestMapper
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\ErrorMapperInterface $errorMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        QuoteRequestMapperInterface $quoteRequestMapper,
        ErrorMapperInterface $errorMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->quoteRequestMapper = $quoteRequestMapper;
        $this->errorMapper = $errorMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer[]|\ArrayObject $errors
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createFailedErrorResponse(ArrayObject $errors): RestResponseInterface
    {
        $restResponse = $this->createRestResponse();

        foreach ($errors as $messageTransfer) {
            $restResponse->addError(
                $this->errorMapper->mapQuoteRequestErrorMessageTransferToRestErrorMessageTransfer(
                    $messageTransfer,
                    new RestErrorMessageTransfer()
                )
            );
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createQuoteRequestRestResponse(QuoteRequestTransfer $quoteRequestTransfer): RestResponseInterface
    {
        return $this->createRestResponse()->addResource($this->createQuoteRequestResource($quoteRequestTransfer));
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createQuoteRequestResource(QuoteRequestTransfer $quoteRequestTransfer): RestResourceInterface
    {
        $cartResource = $this->restResourceBuilder->createRestResource(
            QuoteRequestsRestApiConfig::RESOURCE_QUOTE_REQUESTS,
            $quoteRequestTransfer->getQuoteRequestReference(),
            $this->quoteRequestMapper->mapQuoteRequestTransferToRestQuoteRequestsAttributesTransfer(
                $quoteRequestTransfer,
                new RestQuoteRequestsAttributesTransfer()
            )
        );

        $cartResource->setPayload($quoteRequestTransfer);

        return $cartResource;
    }
}
