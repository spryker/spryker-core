<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Reader\ProductOptionReaderInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Sorter\ProductOptionSorterInterface;
use Spryker\Glue\ProductOptionsRestApi\ProductOptionsRestApiConfig;

class ProductOptionByProductAbstractSkuExpander implements ProductOptionByProductAbstractSkuExpanderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Processor\Reader\ProductOptionReaderInterface
     */
    protected $productOptionReader;

    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Processor\Sorter\ProductOptionSorterInterface
     */
    protected $productOptionSorter;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductOptionsRestApi\Processor\Reader\ProductOptionReaderInterface $productOptionReader
     * @param \Spryker\Glue\ProductOptionsRestApi\Processor\Sorter\ProductOptionSorterInterface $productOptionSorter
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ProductOptionReaderInterface $productOptionReader,
        ProductOptionSorterInterface $productOptionSorter
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
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

        $restProductOptionAttributeTransfers = $this->productOptionReader->getRestProductOptionAttributeTransfersByProductAbstractSkus(
            $productAbstractSkus,
            $restRequest->getMetadata()->getLocale()
        );

        foreach ($restResources as $restResource) {
            if (empty($restProductOptionAttributeTransfers[$restResource->getId()])) {
                continue;
            }

            $sortedRestProductOptionAttributeTransfers = $this->productOptionSorter->sortRestProductOptionAttributesTransfers(
                $restProductOptionAttributeTransfers[$restResource->getId()],
                $restRequest
            );
            $productOptionRestResources = $this->prepareRestResources(
                $sortedRestProductOptionAttributeTransfers
            );

            foreach ($productOptionRestResources as $productOptionRestResource) {
                $restResource->addRelationship($productOptionRestResource);
            }
        }

        return $restResources;
    }

    /**
     * @param \Generated\Shared\Transfer\RestProductOptionAttributesTransfer[] $restProductOptionAttributesTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    protected function prepareRestResourceCollection(array $restProductOptionAttributesTransfers): array
    {
        $restResources = [];

        foreach ($restProductOptionAttributesTransfers as $restProductOptionAttributesTransfer) {
            $restResources[] = $this->restResourceBuilder->createRestResource(
                ProductOptionsRestApiConfig::RESOURCE_PRODUCT_OPTIONS,
                $restProductOptionAttributesTransfer->getSku(),
                $restProductOptionAttributesTransfer
            );
        }

        return $restResources;
    }
}
