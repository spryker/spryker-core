<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductLabelsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductLabelsRestApi\Processor\Reader\ProductLabelReaderInterface;

class ProductLabelByProductConcreteSkuExpander implements ProductLabelByProductConcreteSkuExpanderInterface
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
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $restResources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationships(array $restResources, RestRequestInterface $restRequest): array
    {
        $concreteSkus = array_map(function (RestResourceInterface $restResource) {
            return $restResource->getId();
        }, $restResources);

        $productLabelResources = $this->productLabelReader->getByProductConcreteSkus(
            $concreteSkus,
            $restRequest->getMetadata()->getLocale()
        );

        return $this->addProductLabelsResourceRelationships($restResources, $productLabelResources);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $restResources
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][] $productLabelResources
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    protected function addProductLabelsResourceRelationships(array $restResources, array $productLabelResources): array
    {
        return array_map(function (RestResourceInterface $restResource) use ($productLabelResources) {
            foreach ($productLabelResources[$restResource->getId()] as $productLabelResource) {
                $restResource->addRelationship($productLabelResource);
            }
        }, $restResources);
    }
}
