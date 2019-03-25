<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductTaxSetsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductTaxSetsRestApi\Processor\TaxSets\ProductTaxSetsReaderInterface;

class ProductTaxSetsRelationshipExpander implements ProductTaxSetsRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductTaxSetsRestApi\Processor\TaxSets\ProductTaxSetsReaderInterface
     */
    protected $productTaxSetsReader;

    /**
     * @param \Spryker\Glue\ProductTaxSetsRestApi\Processor\TaxSets\ProductTaxSetsReaderInterface $productTaxSetsReader
     */
    public function __construct(ProductTaxSetsReaderInterface $productTaxSetsReader)
    {
        $this->productTaxSetsReader = $productTaxSetsReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationshipsByResourceId(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $productTaxSetsResource = $this->productTaxSetsReader->findAbstractProductTaxSetsByAbstractProductSku(
                $resource->getId(),
                $restRequest
            );
            if ($productTaxSetsResource !== null) {
                $resource->addRelationship($productTaxSetsResource);
            }
        }
    }
}
