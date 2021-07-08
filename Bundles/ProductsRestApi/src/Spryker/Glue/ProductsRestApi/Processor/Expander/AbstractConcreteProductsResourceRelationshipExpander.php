<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface;

abstract class AbstractConcreteProductsResourceRelationshipExpander implements ConcreteProductsResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface
     */
    protected $concreteProductsReader;

    /**
     * @param \Spryker\Glue\ProductsRestApi\Processor\ConcreteProducts\ConcreteProductsReaderInterface $concreteProductsReader
     */
    public function __construct(ConcreteProductsReaderInterface $concreteProductsReader)
    {
        $this->concreteProductsReader = $concreteProductsReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): array
    {
        $productConcreteSkusGroupedByResourceId = [];
        foreach ($resources as $resource) {
            $productConcreteSkusGroupedByResourceId[$resource->getId()] = $this->findProductConcreteSkusInAttributes($resource);
        }

        $productConcreteSkus = array_unique(array_merge(...array_values($productConcreteSkusGroupedByResourceId)));

        $concreteProductRestResources = $this->concreteProductsReader
            ->getProductConcretesBySkus($productConcreteSkus, $restRequest);

        foreach ($resources as $resource) {
            foreach ($productConcreteSkusGroupedByResourceId[$resource->getId()] as $resourceProductConcreteSku) {
                if (empty($concreteProductRestResources[$resourceProductConcreteSku])) {
                    continue;
                }
                $resource->addRelationship($concreteProductRestResources[$resourceProductConcreteSku]);
            }
        }

        return $resources;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return string[]
     */
    abstract protected function findProductConcreteSkusInAttributes(RestResourceInterface $restResource): array;
}
