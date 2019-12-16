<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ItemTransfer;
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
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartRestResponse(QuoteTransfer $quoteTransfer, string $localeName): RestResponseInterface
    {
        return $this->createRestResponse()->addResource($this->createCartResourceWithItems($quoteTransfer, $localeName));
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
            $restResponse->addResource(
                $this->createCartResourceWithItems(
                    $quoteTransfer,
                    $restRequest->getMetadata()->getLocale()
                )
            );
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createCartResourceWithItems(QuoteTransfer $quoteTransfer, string $localeName): RestResourceInterface
    {
        $cartResource = $this->restResourceBuilder->createRestResource(
            CartsRestApiConfig::RESOURCE_CARTS,
            $quoteTransfer->getUuid(),
            $this->cartMapper->mapQuoteTransferToRestCartsAttributesTransfer($quoteTransfer)
        );

        $cartResource->setPayload($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->addCartItemRelationships($cartResource, $itemTransfer, $localeName);
        }

        return $cartResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $cartResource
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $localeName
     *
     * @return void
     */
    protected function addCartItemRelationships(
        RestResourceInterface $cartResource,
        ItemTransfer $itemTransfer,
        string $localeName
    ): void {
        if (!$this->cartsRestApiConfig->getAllowedCartItemEagerRelationship()) {
            return;
        }

        $itemResource = $this->cartItemResourceBuilder->buildCartItemResource(
            $cartResource,
            $itemTransfer,
            $localeName
        );

        $cartResource->addRelationship($itemResource);
    }
}
