<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistItemsProductsResourceRelationship\Dependency\RestResource;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class WishlistItemsProductsResourceRelationshipToProductsRestApiBridge implements WishlistItemsProductsResourceRelationshipToProductsRestApiInterface
{
    /**
     * @var \Spryker\Glue\ProductsRestApi\ProductsRestApiResourceInterface
     */
    protected $productsResource;

    /**
     * @param \Spryker\Glue\ProductsRestApi\ProductsRestApiResourceInterface $productsResource
     */
    public function __construct($productsResource)
    {
        $this->productsResource = $productsResource;
    }

    /**
     * @param string $concreteProductSku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findProductConcreteBySku($concreteProductSku, $restRequest): ?RestResourceInterface
    {
        return $this->productsResource->findProductConcreteBySku($concreteProductSku, $restRequest);
    }
}
