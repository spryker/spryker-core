<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface;
use Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\ItemResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartItemByQuoteResourceRelationshipExpander implements CartItemByQuoteResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface
     */
    protected $cartReader;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\ItemResponseBuilderInterface
     */
    protected $itemResponseBuilder;

    /**
     * @var \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CartItemFilterPluginInterface[]
     */
    protected $cartItemFilterPlugins;

    /**
     * @param \Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface $cartReader
     * @param \Spryker\Glue\CartsRestApiExtension\Dependency\Plugin\CartItemFilterPluginInterface[] $cartItemFilterPlugins
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\ItemResponseBuilderInterface $itemResponseBuilder
     */
    public function __construct(
        CartReaderInterface $cartReader,
        array $cartItemFilterPlugins,
        ItemResponseBuilderInterface $itemResponseBuilder
    ) {
        $this->cartReader = $cartReader;
        $this->cartItemFilterPlugins = $cartItemFilterPlugins;
        $this->itemResponseBuilder = $itemResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            /**
             * @var \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
             */
            $quoteTransfer = $resource->getPayload();
            if (!$quoteTransfer instanceof QuoteTransfer) {
                continue;
            }

            $filteredItemTransfers = $this->executeCartItemFilterPlugins(
                $quoteTransfer->getItems()->getArrayCopy(),
                $quoteTransfer
            );

            foreach ($filteredItemTransfers as $itemTransfer) {
                $itemResource = $this->itemResponseBuilder->createCartItemResource(
                    $resource,
                    $itemTransfer,
                    $restRequest->getMetadata()->getLocale()
                );

                $resource->addRelationship($itemResource);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addGuestCartItemResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            /**
             * @var \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
             */
            $quoteTransfer = $resource->getPayload();
            if (!$quoteTransfer instanceof QuoteTransfer) {
                continue;
            }

            $filteredItemTransfers = $this->executeCartItemFilterPlugins(
                $quoteTransfer->getItems()->getArrayCopy(),
                $quoteTransfer
            );

            foreach ($filteredItemTransfers as $itemTransfer) {
                $itemResource = $this->itemResponseBuilder->createGuestCartItemResource(
                    $resource,
                    $itemTransfer,
                    $restRequest->getMetadata()->getLocale()
                );

                $resource->addRelationship($itemResource);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function executeCartItemFilterPlugins(array $itemTransfers, QuoteTransfer $quoteTransfer): array
    {
        foreach ($this->cartItemFilterPlugins as $cartItemFilterPlugin) {
            $itemTransfers = $cartItemFilterPlugin->filterCartItems($itemTransfers, $quoteTransfer);
        }

        return $itemTransfers;
    }
}
