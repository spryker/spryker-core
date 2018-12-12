<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAlternativesRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductAlternativesRestApi\Processor\ProductAlternative\ProductAlternativeReaderInterface;

class ProductAvailabilityResourceRelationshipExpander implements ProductAvailabilityResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductAlternativesRestApi\Processor\ProductAlternative\ProductAlternativeReaderInterface
     */
    protected $productAlternativeReader;

    /**
     * @param \Spryker\Glue\ProductAlternativesRestApi\Processor\ProductAlternative\ProductAlternativeReaderInterface $productAlternativeReader
     */
    public function __construct(ProductAlternativeReaderInterface $productAlternativeReader)
    {
        $this->productAlternativeReader = $productAlternativeReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addRelationshipsByConcreteSku(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $concreteSku = $resource->getId();

            $productAlternative = $this->productAlternativeReader->findConcreteProductAlternativeBySku($concreteSku, $restRequest);
            if ($productAlternative) {
                $resource->addRelationship($productAlternative);
            }
        }

        return $resources;
    }
}
