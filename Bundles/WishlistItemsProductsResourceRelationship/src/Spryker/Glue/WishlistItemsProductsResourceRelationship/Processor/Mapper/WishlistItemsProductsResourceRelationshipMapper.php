<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistItemsProductsResourceRelationship\Processor\Mapper;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\WishlistItemsProductsResourceRelationship\Dependency\RestResource\WishlistItemsProductsResourceRelationshipToProductsRestApiInterface;

class WishlistItemsProductsResourceRelationshipMapper implements WishlistItemsProductsResourceRelationshipMapperInterface
{
    /**
     * @var \Spryker\Glue\WishlistItemsProductsResourceRelationship\Dependency\RestResource\WishlistItemsProductsResourceRelationshipToProductsRestApiInterface
     */
    protected $productsResource;

    /**
     * WishlistsProductsResourceRelationshipMapper constructor.
     *
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
    public function mapResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $concreteProduct = $this->productsResource->findOneByProductConcreteSku($resource->getAttributes()['sku'], $restRequest);
            if ($concreteProduct !== null) {
                $resource->addRelationship($concreteProduct);
            }
        }
    }
}
