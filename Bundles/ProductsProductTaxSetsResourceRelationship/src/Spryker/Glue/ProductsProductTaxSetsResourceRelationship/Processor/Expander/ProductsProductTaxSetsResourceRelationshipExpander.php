<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductTaxSetsResourceRelationship\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsProductTaxSetsResourceRelationship\Dependency\RestResource\ProductsProductTaxSetsResourceRelationshipToTaxSetsRestApiResourceInterface;

class ProductsProductTaxSetsResourceRelationshipExpander implements ProductsProductTaxSetsResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductsProductTaxSetsResourceRelationship\Dependency\RestResource\ProductsProductTaxSetsResourceRelationshipToTaxSetsRestApiResourceInterface
     */
    protected $productTaxSetsResource;

    /**
     * @param \Spryker\Glue\ProductsProductTaxSetsResourceRelationship\Dependency\RestResource\ProductsProductTaxSetsResourceRelationshipToTaxSetsRestApiResourceInterface $productTaxSetsResource
     */
    public function __construct(ProductsProductTaxSetsResourceRelationshipToTaxSetsRestApiResourceInterface $productTaxSetsResource)
    {
        $this->productTaxSetsResource = $productTaxSetsResource;
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
            $abstractProductSku = $resource->getId();
            $productTaxSetsResource = $this->productTaxSetsResource
                ->findAbstractProductTaxSetsByAbstractProductSku($abstractProductSku, $restRequest);
            if ($productTaxSetsResource !== null) {
                $resource->addRelationship($productTaxSetsResource);
            }
        }
    }
}
