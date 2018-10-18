<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductPricesResourceRelationship\Dependency\RestResource;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class ProductsProductPricesResourceRelationToProductPricesRestApiBridge implements ProductsProductPricesResourceRelationToProductPricesRestApiInterface
{
    /**
     * @var \Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiResourceInterface
     */
    protected $productPricesRestApiResource;

    /**
     * @param \Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiResourceInterface $productPricesRestApiResource
     */
    public function __construct($productPricesRestApiResource)
    {
        $this->productPricesRestApiResource = $productPricesRestApiResource;
    }

    /**
     * @param string $abstractProductSku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findAbstractProductPricesByAbstractProductSku($abstractProductSku, $restRequest): ?RestResourceInterface
    {
        return $this->productPricesRestApiResource
            ->findAbstractProductPricesByAbstractProductSku($abstractProductSku, $restRequest);
    }

    /**
     * @param string $concreteProductSku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findConcreteProductPricesByConcreteProductSku($concreteProductSku, $restRequest): ?RestResourceInterface
    {
        return $this->productPricesRestApiResource
            ->findConcreteProductPricesByConcreteProductSku($concreteProductSku, $restRequest);
    }
}
