<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

class GuestCartRestResponseBuilder implements GuestCartRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartMapperInterface
     */
    protected $cartMapper;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\ItemResponseBuilderInterface
     */
    protected $itemResponseBuilder;

    /**
     * @var \Spryker\Glue\CartsRestApi\CartsRestApiConfig
     */
    protected $cartsRestApiConfig;

    /**
     * @var \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CartItemFilterPluginInterface[]
     */
    protected $cartItemFilterPlugins;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartMapperInterface $cartMapper
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\ItemResponseBuilderInterface $itemResponseBuilder
     * @param \Spryker\Glue\CartsRestApi\CartsRestApiConfig $cartsRestApiConfig
     * @param \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CartItemFilterPluginInterface[] $cartItemFilterPlugins
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CartMapperInterface $cartMapper,
        ItemResponseBuilderInterface $itemResponseBuilder,
        CartsRestApiConfig $cartsRestApiConfig,
        array $cartItemFilterPlugins
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->cartMapper = $cartMapper;
        $this->itemResponseBuilder = $itemResponseBuilder;
        $this->cartsRestApiConfig = $cartsRestApiConfig;
        $this->cartItemFilterPlugins = $cartItemFilterPlugins;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createEmptyGuestCartRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createGuestCartRestResponse(QuoteTransfer $quoteTransfer, string $localeName): RestResponseInterface
    {
        $guestCartResource = $this->restResourceBuilder->createRestResource(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS,
            $quoteTransfer->getUuid(),
            $this->cartMapper->mapQuoteTransferToRestCartsAttributesTransfer($quoteTransfer)
        );

        $guestCartResource->setPayload($quoteTransfer);

        $guestCartResource = $this->addGuestCartItemsRelationship(
            $guestCartResource,
            $quoteTransfer,
            $localeName
        );

        return $this->createEmptyGuestCartRestResponse()->addResource($guestCartResource);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteErrorTransfer[]|\ArrayObject $errors
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createFailedErrorResponse(ArrayObject $errors): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($errors as $quoteErrorTransfer) {
            $restResponse->addError(
                $this->cartMapper->mapQuoteErrorTransferToRestErrorMessageTransfer(
                    $quoteErrorTransfer,
                    new RestErrorMessageTransfer()
                )
            );
        }

        return $restResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $guestCartResource
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function addGuestCartItemsRelationship(
        RestResourceInterface $guestCartResource,
        QuoteTransfer $quoteTransfer,
        string $localeName
    ): RestResourceInterface {
        if (!$this->cartsRestApiConfig->getAllowedGuestCartItemEagerRelationship()) {
            return $guestCartResource;
        }

        $filteredItemTransfers = $this->executeCartItemFilterPlugins(
            $quoteTransfer->getItems()->getArrayCopy(),
            $quoteTransfer
        );

        foreach ($filteredItemTransfers as $itemTransfer) {
            $guestCartResource->addRelationship(
                $this->itemResponseBuilder->createGuestCartItemResource(
                    $guestCartResource,
                    $itemTransfer,
                    $localeName
                )
            );
        }

        return $guestCartResource;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function executeCartItemFilterPlugins(array $itemTransfers, QuoteTransfer $quoteTransfer): array
    {
        if (!$this->cartItemFilterPlugins) {
            return $itemTransfers;
        }

        $filteredItemTransfers = [];
        foreach ($this->cartItemFilterPlugins as $cartItemFilterPlugin) {
            $filteredItemTransfers = $cartItemFilterPlugin->filterCartItems($itemTransfers, $quoteTransfer);
        }

        return $filteredItemTransfers;
    }
}
