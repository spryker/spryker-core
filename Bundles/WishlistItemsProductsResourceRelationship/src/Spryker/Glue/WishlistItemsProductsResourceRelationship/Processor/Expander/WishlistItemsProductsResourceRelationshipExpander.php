<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistItemsProductsResourceRelationship\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\WishlistItemsProductsResourceRelationship\Dependency\RestResource\WishlistItemsProductsResourceRelationshipToProductsRestApiInterface;

class WishlistItemsProductsResourceRelationshipExpander implements WishlistItemsProductsResourceRelationshipExpanderInterface
{
    protected const KEY_SKU = 'sku';

    /**
     * @var \Spryker\Glue\WishlistItemsProductsResourceRelationship\Dependency\RestResource\WishlistItemsProductsResourceRelationshipToProductsRestApiInterface
     */
    protected $productsResource;

    /**
     * @param \Spryker\Glue\WishlistItemsProductsResourceRelationship\Dependency\RestResource\WishlistItemsProductsResourceRelationshipToProductsRestApiInterface $productsResource
     */
    public function __construct(WishlistItemsProductsResourceRelationshipToProductsRestApiInterface $productsResource)
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
            if (isset($resource->getAttributes()[static::KEY_SKU])) {
                $concreteProduct = $this->productsResource->findProductConcreteBySku($resource->getAttributes()[static::KEY_SKU], $restRequest);
                if ($concreteProduct !== null) {
                    $resource->addRelationship($concreteProduct);
                }
            }
        }
    }
}
