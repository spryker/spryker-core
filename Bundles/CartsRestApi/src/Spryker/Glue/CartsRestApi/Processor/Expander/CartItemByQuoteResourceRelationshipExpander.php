<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface;
use Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartItemByQuoteResourceRelationshipExpander implements CartItemByQuoteResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface
     */
    protected $cartReader;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemResourceBuilderInterface
     */
    protected $cartItemResourceBuilder;

    /**
     * @param \Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface $cartReader
     * @param \Spryker\Glue\CartsRestApi\Processor\CartItem\CartItemResourceBuilderInterface $cartItemResourceBuilder
     */
    public function __construct(
        CartReaderInterface $cartReader,
        CartItemResourceBuilderInterface $cartItemResourceBuilder
    ) {
        $this->cartReader = $cartReader;
        $this->cartItemResourceBuilder = $cartItemResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            /**
             * @var \Generated\Shared\Transfer\QuoteTransfer|null $payload
             */
            $quoteTransfer = $resource->getPayload();
            if (!$quoteTransfer instanceof QuoteTransfer) {
                continue;
            }

            foreach ($quoteTransfer->getItems() as $itemTransfer) {
                $itemResource = $this->cartItemResourceBuilder->buildCartItemResource(
                    $resource,
                    $itemTransfer,
                    $restRequest->getMetadata()->getLocale()
                );

                $resource->addRelationship($itemResource);
            }
        }

        return $resources;
    }
}
