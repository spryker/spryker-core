<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Builder;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Reader\StorageReaderInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Sorter\ProductOptionSorterInterface;
use Spryker\Glue\ProductOptionsRestApi\ProductOptionsRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

class ProductOptionRestResourceBuilder implements ProductOptionRestResourceBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Processor\Reader\StorageReaderInterface
     */
    protected $storageReader;

    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Processor\Sorter\ProductOptionSorterInterface
     */
    protected $productOptionSorter;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductOptionsRestApi\Processor\Reader\StorageReaderInterface $storageReader
     * @param \Spryker\Glue\ProductOptionsRestApi\Processor\Sorter\ProductOptionSorterInterface $productOptionSorter
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        StorageReaderInterface $storageReader,
        ProductOptionSorterInterface $productOptionSorter
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->storageReader = $storageReader;
        $this->productOptionSorter = $productOptionSorter;
    }

    /**
     * @param string[] $productAbstractSkus
     * @param string $localeName
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[] $sorts
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    public function getProductOptionsByProductAbstractSkus(
        array $productAbstractSkus,
        string $localeName,
        array $sorts
    ): array {
        $restProductOptionAttributesTransfers =
            $this->storageReader->getRestProductOptionAttributesTransfersByProductAbstractSkus(
                $productAbstractSkus,
                $localeName
            );

        return $this->createProductOptionRestResources(
            $restProductOptionAttributesTransfers,
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            $sorts
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestProductOptionAttributesTransfer[] $restProductOptionAttributesTransfers
     * @param string $parentResourceType
     * @param string $parentResourceSku
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    protected function prepareRestResources(
        array $restProductOptionAttributesTransfers,
        string $parentResourceType,
        string $parentResourceSku
    ): array {
        $restResources = [];
        foreach ($restProductOptionAttributesTransfers as $restProductOptionAttributesTransfer) {
            $restResource = $this->restResourceBuilder->createRestResource(
                ProductOptionsRestApiConfig::RESOURCE_PRODUCT_OPTIONS,
                $restProductOptionAttributesTransfer->getSku(),
                $restProductOptionAttributesTransfer
            );
            $restResource->addLink(
                RestLinkInterface::LINK_SELF,
                $this->generateSelfLink($parentResourceType, $parentResourceSku, $restProductOptionAttributesTransfer->getSku())
            );
            $restResources[] = $restResource;
        }

        return $restResources;
    }

    /**
     * @param string $parentResourceType
     * @param string $parentResourceSku
     * @param string $productOptionSku
     *
     * @return string
     */
    protected function generateSelfLink(string $parentResourceType, string $parentResourceSku, string $productOptionSku): string
    {
        return sprintf(
            '%s/%s/%s/%s',
            $parentResourceType,
            $parentResourceSku,
            ProductOptionsRestApiConfig::RESOURCE_PRODUCT_OPTIONS,
            $productOptionSku
        );
    }

    /**
     * @param string[] $productConcreteSkus
     * @param string $localeName
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[] $sorts
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    public function getProductOptionsByProductConcreteSkus(
        array $productConcreteSkus,
        string $localeName,
        array $sorts
    ): array {
        $restProductOptionAttributesTransfers =
            $this->storageReader->getRestProductOptionAttributesTransfersByProductConcreteSkus(
                $productConcreteSkus,
                $localeName
            );

        return $this->createProductOptionRestResources(
            $restProductOptionAttributesTransfers,
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $sorts
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RestProductOptionAttributesTransfer[][] $restProductOptionAttributesTransfersCollection
     * @param string $parentResourceType
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[] $sorts $sorts
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    protected function createProductOptionRestResources(
        array $restProductOptionAttributesTransfersCollection,
        string $parentResourceType,
        array $sorts
    ): array {
        $productOptionRestResources = [];
        foreach ($restProductOptionAttributesTransfersCollection as $productSku => $restProductOptionAttributesTransfers) {
            $restProductOptionAttributesTransfers = $this->productOptionSorter->sortRestProductOptionAttributesTransfers(
                $restProductOptionAttributesTransfers,
                $sorts
            );
            $productOptionRestResources[$productSku] = $this->prepareRestResources(
                $restProductOptionAttributesTransfers,
                $parentResourceType,
                $productSku
            );
        }

        return $productOptionRestResources;
    }
}
