<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAlternativesRestApi\Dependency\Resource;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ProductAlternativesRestApiToProductsRestApiResourceBridge implements ProductAlternativesRestApiToProductsRestApiResourceInterface
{
    /**
     * @var \Spryker\Glue\ProductsRestApi\ProductsRestApiResourceInterface
     */
    protected $productsRestApiResource;

    /**
     * @param \Spryker\Glue\ProductsRestApi\ProductsRestApiResourceInterface $productsRestApiResource
     */
    public function __construct($productsRestApiResource)
    {
        $this->productsRestApiResource = $productsRestApiResource;
    }

    /**
     * @param int $idProductAbstract
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findAbstractProductById(int $idProductAbstract, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        return $this->productsRestApiResource->findAbstractProductById($idProductAbstract, $restRequest);
    }

    /**
     * @param int $idProductConcrete
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findConcreteProductById(int $idProductConcrete, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        return $this->productsRestApiResource->findConcreteProductById($idProductConcrete, $restRequest);
    }
}
