<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAlternativesRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductAlternativesRestApi\Processor\ProductAlternative\AlternativeProductReaderInterface;

class AlternativeProductResourceRelationshipExpander implements AlternativeProductResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductAlternativesRestApi\Processor\ProductAlternative\AlternativeProductReaderInterface
     */
    protected $productAlternativeReader;

    /**
     * @param \Spryker\Glue\ProductAlternativesRestApi\Processor\ProductAlternative\AlternativeProductReaderInterface $productAlternativeReader
     */
    public function __construct(AlternativeProductReaderInterface $productAlternativeReader)
    {
        $this->productAlternativeReader = $productAlternativeReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addRelationshipsByResourceId(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $concreteSku = $resource->getId();

            $productAlternativeResource = $this->productAlternativeReader->findConcreteProductAlternativeBySku($concreteSku, $restRequest);
            if ($productAlternativeResource) {
                $resource->addRelationship($productAlternativeResource);
            }
        }

        return $resources;
    }
}
