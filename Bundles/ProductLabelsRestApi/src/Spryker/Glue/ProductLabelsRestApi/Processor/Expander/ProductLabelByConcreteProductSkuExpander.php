<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductLabelsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductLabelsRestApi\Processor\Reader\ProductLabelReaderInterface;

class ProductLabelByConcreteProductSkuExpander implements ProductLabelByConcreteProductSkuExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductLabelsRestApi\Processor\Reader\ProductLabelReaderInterface
     */
    protected $productLabelReader;

    /**
     * @param \Spryker\Glue\ProductLabelsRestApi\Processor\Reader\ProductLabelReaderInterface $productLabelReader
     */
    public function __construct(ProductLabelReaderInterface $productLabelReader)
    {
        $this->productLabelReader = $productLabelReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): array
    {
        $concreteSkuList = array_map(function ($resource) {
            return $resource->getId();
        }, $resources);

        $productLabels = $this->productLabelReader->findLabelByConcreteProductSkuList(
            $concreteSkuList,
            $restRequest->getMetadata()->getLocale()
        );

        return $this->addProductLabelsToResources($resources, $productLabels);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][] $productLabels
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    protected function addProductLabelsToResources(array $resources, array $productLabels): array
    {
        return array_map(function ($resource) use ($productLabels) {
            foreach ($productLabels[$resource->getId()] as $productLabel) {
                $resource->addRelationship($productLabel);
            }
        }, $resources);
    }
}
