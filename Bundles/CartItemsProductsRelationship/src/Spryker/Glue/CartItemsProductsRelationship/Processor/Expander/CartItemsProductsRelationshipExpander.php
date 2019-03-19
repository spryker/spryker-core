<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartItemsProductsRelationship\Processor\Expander;

use Spryker\Glue\CartItemsProductsRelationship\Dependency\RestResource\CartItemsProductsRelationToProductsRestApiInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class CartItemsProductsRelationshipExpander implements CartItemsProductsRelationshipExpanderInterface
{
    protected const KEY_SKU = 'sku';

    /**
     * @var \Spryker\Glue\CartItemsProductsRelationship\Dependency\RestResource\CartItemsProductsRelationToProductsRestApiInterface
     */
    protected $productsResource;

    /**
     * @param \Spryker\Glue\CartItemsProductsRelationship\Dependency\RestResource\CartItemsProductsRelationToProductsRestApiInterface $productsResource
     */
    public function __construct(CartItemsProductsRelationToProductsRestApiInterface $productsResource)
    {
        $this->productsResource = $productsResource;
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
            $sku = $this->findSku($resource->getAttributes());
            if (!$sku) {
                continue;
            }

            $productResource = $this->productsResource->findProductConcreteBySku($sku, $restRequest);

            if ($productResource !== null) {
                $resource->addRelationship($productResource);
            }
        }
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $attributes
     *
     * @return string|null
     */
    protected function findSku(?AbstractTransfer $attributes): ?string
    {
        if ($attributes
            && $attributes->offsetExists(static::KEY_SKU)
            && $attributes->offsetGet(static::KEY_SKU)
        ) {
            return $attributes->offsetGet(static::KEY_SKU);
        }

        return null;
    }
}
