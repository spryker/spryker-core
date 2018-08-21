<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsTaxSetsResourceRelationship\Processor\Mapper;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsTaxSetsResourceRelationship\Dependency\RestResource\ProductsTaxSetsResourceRelationshipToTaxSetsRestApiInterface;

class ProductsTaxSetsResourceRelationshipMapper implements ProductsTaxSetsResourceRelationshipMapperInterface
{
    /**
     * @var \Spryker\Glue\ProductsTaxSetsResourceRelationship\Dependency\RestResource\ProductsTaxSetsResourceRelationshipToTaxSetsRestApiInterface
     */
    protected $productTaxSetsResource;

    /**
     * @param \Spryker\Glue\ProductsTaxSetsResourceRelationship\Dependency\RestResource\ProductsTaxSetsResourceRelationshipToTaxSetsRestApiInterface $productTaxSetsResource
     */
    public function __construct(ProductsTaxSetsResourceRelationshipToTaxSetsRestApiInterface $productTaxSetsResource)
    {
        $this->productTaxSetsResource = $productTaxSetsResource;
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
            $abstractProductSku = $resource->getId();
            $productTaxSetsResource = $this->productTaxSetsResource
                ->findAbstractProductTaxSetsByAbstractProductSku($abstractProductSku, $restRequest);
            if ($productTaxSetsResource !== null) {
                $resource->addRelationship($productTaxSetsResource);
            }
        }
    }
}
