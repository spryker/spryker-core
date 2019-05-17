<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductTaxSetsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductTaxSetsRestApi\Processor\ProductTaxSet\ProductTaxSetReaderInterface;

class ProductTaxSetRelationshipExpander implements ProductTaxSetRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductTaxSetsRestApi\Processor\ProductTaxSet\ProductTaxSetReaderInterface
     */
    protected $productTaxSetReader;

    /**
     * @param \Spryker\Glue\ProductTaxSetsRestApi\Processor\ProductTaxSet\ProductTaxSetReaderInterface $productTaxSetReader
     */
    public function __construct(ProductTaxSetReaderInterface $productTaxSetReader)
    {
        $this->productTaxSetReader = $productTaxSetReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationshipsByResourceId(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $productTaxSetsResource = $this->productTaxSetReader->findProductAbstractTaxSetsByProductAbstractSku(
                $resource->getId(),
                $restRequest
            );

            if ($productTaxSetsResource) {
                $resource->addRelationship($productTaxSetsResource);
            }
        }

        return $resources;
    }
}
