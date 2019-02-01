<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Cart;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Spryker\Client\CartsRestApi\CartsRestApiClientInterface;
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
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\CartRestResponseBuilderInterface $cartRestResponseBuilder
     * @param \Spryker\Client\CartsRestApi\CartsRestApiClientInterface $cartsRestApiClient
     */
    public function __construct(
        CartRestResponseBuilderInterface $cartRestResponseBuilder,
        CartsRestApiClientInterface $cartsRestApiClient
    ) {
        $this->cartRestResponseBuilder = $cartRestResponseBuilder;
        $this->cartsRestApiClient = $cartsRestApiClient;
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

        $restQuoteRequestTransfer = (new RestQuoteRequestTransfer())
            ->setQuoteUuid($uuidQuote)
            ->setQuote((new QuoteTransfer())->setUuid($uuidQuote))
            ->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());

        $quoteResponseTransfer = $this->cartsRestApiClient->deleteQuote($restQuoteRequestTransfer);
        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->cartRestResponseBuilder->createFailedDeletingCartErrorResponse();
        }

        return $this->cartRestResponseBuilder->createRestResponse();
    }
}
