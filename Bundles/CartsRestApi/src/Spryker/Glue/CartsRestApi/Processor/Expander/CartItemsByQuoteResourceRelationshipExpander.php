<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface;
use Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartItemsByQuoteResourceRelationshipExpander implements CartItemsByQuoteResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemMapperInterface
     */
    protected $cartItemsMapper;

    /**
     * @var \Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface
     */
    protected $cartReader;

    /**
     * @param \Spryker\Glue\CartsRestApi\Processor\Cart\CartReaderInterface $cartReader
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CartsRestApi\Processor\Mapper\CartItemMapperInterface $cartItemsMapper
     */
    public function __construct(
        CartReaderInterface $cartReader,
        RestResourceBuilderInterface $restResourceBuilder,
        CartItemMapperInterface $cartItemsMapper
    ) {
        $this->cartReader = $cartReader;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->cartItemsMapper = $cartItemsMapper;
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
            if ($quoteTransfer === null || !($quoteTransfer instanceof QuoteTransfer)) {
                continue;
            }

            foreach ($quoteTransfer->getItems() as $itemTransfer) {
                $itemResource = $this->restResourceBuilder->createRestResource(
                    CartsRestApiConfig::RESOURCE_CART_ITEMS,
                    $itemTransfer->getGroupKey(),
                    $this->cartItemsMapper->mapItemTransferToRestItemsAttributesTransfer(
                        $itemTransfer,
                        $restRequest->getMetadata()->getLocale()
                    )
                );

                $itemResource->addLink(
                    RestLinkInterface::LINK_SELF,
                    sprintf(
                        '%s/%s/%s/%s',
                        CartsRestApiConfig::RESOURCE_CARTS,
                        $resource->getId(),
                        CartsRestApiConfig::RESOURCE_CART_ITEMS,
                        $itemTransfer->getGroupKey()
                    )
                );

                $resource->addRelationship($itemResource);
            }
        }

        return $resources;
    }
}
