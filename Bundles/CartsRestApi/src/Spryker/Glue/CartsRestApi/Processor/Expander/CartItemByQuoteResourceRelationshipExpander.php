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
     * @param \Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface $cartReader
     * @param \Spryker\Glue\CartsRestApi\Processor\RestResponseBuilder\ItemResponseBuilderInterface $itemResponseBuilder
     */
    public function __construct(
        CartReaderInterface $cartReader,
        ItemResponseBuilderInterface $itemResponseBuilder
    ) {
        $this->cartReader = $cartReader;
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

            foreach ($quoteTransfer->getItems() as $itemTransfer) {
                $itemResource = $this->itemResponseBuilder->createCartItemResource(
                    $resource,
                    $itemTransfer,
                    $restRequest->getMetadata()->getLocale()
                );

                $resource->addRelationship($itemResource);
            }
        }
    }
}
