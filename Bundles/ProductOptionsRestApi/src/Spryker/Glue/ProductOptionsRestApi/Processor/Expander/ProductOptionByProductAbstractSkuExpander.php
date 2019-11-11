<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Reader\ProductOptionReaderInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Sorter\ProductOptionSorterInterface;

class ProductOptionByProductAbstractSkuExpander implements ProductOptionByProductAbstractSkuExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Processor\Reader\ProductOptionReaderInterface
     */
    protected $productOptionReader;

    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Processor\Sorter\ProductOptionSorterInterface
     */
    protected $productOptionSorter;

    /**
     * @param \Spryker\Glue\ProductOptionsRestApi\Processor\Reader\ProductOptionReaderInterface $productOptionReader
     * @param \Spryker\Glue\ProductOptionsRestApi\Processor\Sorter\ProductOptionSorterInterface $productOptionSorter
     */
    public function __construct(
        ProductOptionReaderInterface $productOptionReader,
        ProductOptionSorterInterface $productOptionSorter
    ) {
        $this->productOptionReader = $productOptionReader;
        $this->productOptionSorter = $productOptionSorter;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $restResources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationships(array $restResources, RestRequestInterface $restRequest): array
    {
        $productAbstractSkus = array_map(function (RestResourceInterface $restResource) {
            return $restResource->getId();
        }, $restResources);

        $productOptionRestResources = $this->productOptionReader->getByProductAbstractSkus(
            $productAbstractSkus,
            $restRequest->getMetadata()->getLocale()
        );

        foreach ($restResources as $restResource) {
            if (empty($productOptionRestResources[$restResource->getId()])) {
                continue;
            }

            $this->productOptionSorter->sortRestProductOptionsAttributesTransfers(
                $productOptionRestResources[$restResource->getId()],
                $restRequest
            );

            foreach ($productOptionRestResources[$restResource->getId()] as $productOptionRestResource) {
                $restResource->addRelationship($productOptionRestResource);
            }
        }

        return $restResources;
    }
}
