<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WishlistsProductsResourceRelationship\Dependency\RestResource;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class WishlistsProductsResourceRelationshipToProductsRestApiBridge implements WishlistsProductsResourceRelationshipToProductsRestApiInterface
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
    public function findOneByProductConcreteSku($concreteProductSku, $restRequest): ?RestResourceInterface
    {
        return $this->productsResource->findOneByProductConcreteSku($concreteProductSku, $restRequest);
    }
}
