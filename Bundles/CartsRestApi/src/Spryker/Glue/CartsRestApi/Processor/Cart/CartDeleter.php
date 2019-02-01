<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Cart;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartDeleter implements CartDeleterInterface
{
    /**
     * @var \Spryker\Client\CartsRestApi\CartsRestApiClientInterface
     */
    protected $cartsRestApiClient;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface
     */
    protected $cartRestResponseBuilder;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface
     */
    protected $cartsResourceMapper;

    /**
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface $cartRestResponseBuilder
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartsResourceMapperInterface $cartsResourceMapper
     */
    public function __construct(
        CartRestResponseBuilderInterface $cartRestResponseBuilder,
        CartsRestApiClientInterface $cartsRestApiClient,
        CartsResourceMapperInterface $cartsResourceMapper
    ) {
        $this->cartRestResponseBuilder = $cartRestResponseBuilder;
        $this->cartsRestApiClient = $cartsRestApiClient;
        $this->cartsResourceMapper = $cartsResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function delete(RestRequestInterface $restRequest): RestResponseInterface
    {
        $uuidQuote = $restRequest->getResource()->getId();
        if ($uuidQuote === null) {
            return $this->cartRestResponseBuilder->createCartIdMissingErrorResponse();
        }

        $quoteTransfer = (new QuoteTransfer())->setUuid($uuidQuote);

        $quoteResponseTransfer = $this->cartsRestApiClient->deleteQuote(
            $this->cartsResourceMapper->createRestQuoteRequestTransfer($restRequest, $quoteTransfer)
        );

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->cartRestResponseBuilder->createFailedDeletingCartErrorResponse();
        }

        return $this->cartRestResponseBuilder->createRestResponse();
    }
}
