<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductPricesResourceRelationship\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsProductPricesResourceRelationship\Dependency\RestResource\ProductsProductPricesResourceRelationToProductPricesRestApiInterface;

class AbstractProductsProductPricesResourceRelationshipExpander implements AbstractProductsProductPricesResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductsProductPricesResourceRelationship\Dependency\RestResource\ProductsProductPricesResourceRelationToProductPricesRestApiInterface
     */
    protected $productPricesRestApiResource;

    /**
     * @param \Spryker\Glue\ProductsProductPricesResourceRelationship\Dependency\RestResource\ProductsProductPricesResourceRelationToProductPricesRestApiInterface $productPricesRestApiResource
     */
    public function __construct(ProductsProductPricesResourceRelationToProductPricesRestApiInterface $productPricesRestApiResource)
    {
        $this->productPricesRestApiResource = $productPricesRestApiResource;
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
            $abstractProductPricesResource = $this->productPricesRestApiResource
                ->findAbstractProductPricesByAbstractProductSku($resource->getId(), $restRequest);
            if ($abstractProductPricesResource) {
                $resource->addRelationship($abstractProductPricesResource);
            }
        }
    }
}
