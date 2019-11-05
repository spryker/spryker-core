<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\QuoteCollectionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartRestResponseBuilder extends AbstractCartRestResponseBuilder implements CartRestResponseBuilderInterface
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartRestResponse(QuoteTransfer $quoteTransfer, RestRequestInterface $restRequest): RestResponseInterface
    {
        return $this->createRestResponse()->addResource($this->createCartResourceWithItems($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteCollectionTransfer $quoteCollectionTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestQuoteCollectionResponse(
        QuoteCollectionTransfer $quoteCollectionTransfer,
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        $restResponse = $this->createRestResponse();
        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            $restResponse->addResource($this->createCartResourceWithItems($quoteTransfer));
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createCartResourceWithItems(QuoteTransfer $quoteTransfer): RestResourceInterface
    {
        $cartResource = $this->restResourceBuilder->createRestResource(
            CartsRestApiConfig::RESOURCE_CARTS,
            $quoteTransfer->getUuid(),
            $this->cartsMapper->mapQuoteTransferToRestCartsAttributesTransfer($quoteTransfer)
        );

        $cartResource->setPayload($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemResource = $this->restResourceBuilder->createRestResource(
                CartsRestApiConfig::RESOURCE_CART_ITEMS,
                $itemTransfer->getGroupKey(),
                $this->cartItemsMapper->mapItemTransferToRestItemsAttributesTransfer($itemTransfer)
            );

            $itemResource->addLink(
                static::KEY_REST_RESOURCE_SELF_LINK,
                sprintf(
                    static::PATTERN_GUEST_CART_ITEM_RESOURCE_SELF_LINK,
                    CartsRestApiConfig::RESOURCE_CARTS,
                    $cartResource->getId(),
                    CartsRestApiConfig::RESOURCE_CART_ITEMS,
                    $itemTransfer->getGroupKey()
                )
            );

            $cartResource->addRelationship($itemResource);
        }

        return $cartResource;
    }
}
