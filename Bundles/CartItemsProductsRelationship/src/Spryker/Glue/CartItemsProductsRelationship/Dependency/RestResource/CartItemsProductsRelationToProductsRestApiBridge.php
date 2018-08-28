<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartItemsProductsRelationship\Dependency\RestResource;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartItemsProductsRelationToProductsRestApiBridge implements CartItemsProductsRelationToProductsRestApiInterface
{
    /**
     * @var \Spryker\Glue\ProductsRestApi\ProductsRestApiResourceInterface
     */
    protected $productsRestResource;

    /**
     * @param \Spryker\Glue\ProductsRestApi\ProductsRestApiResourceInterface $productsRestResource
     */
    public function __construct($productsRestResource)
    {
        $this->productsRestResource = $productsRestResource;
    }

    /**
     * @param string $productIdentifier
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findByConcreteProductSku(string $productIdentifier, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        return $this->productsRestResource->findProductConcreteBySku($productIdentifier, $restRequest);
    }
}
