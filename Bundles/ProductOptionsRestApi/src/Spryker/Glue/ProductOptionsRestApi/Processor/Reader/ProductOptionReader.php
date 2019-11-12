<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Reader;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductOptionStorageClientInterface;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Mapper\ProductOptionMapperInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Sorter\ProductOptionSorterInterface;
use Spryker\Glue\ProductOptionsRestApi\ProductOptionsRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

class ProductOptionReader implements ProductOptionReaderInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductOptionStorageClientInterface
     */
    protected $productOptionStorageClient;

    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Processor\Mapper\ProductOptionMapperInterface
     */
    protected $productOptionMapper;

    /**
     * @var \Spryker\Glue\ProductOptionsRestApi\Processor\Sorter\ProductOptionSorterInterface
     */
    protected $productOptionSorter;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductOptionStorageClientInterface $productOptionStorageClient
     * @param \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \Spryker\Glue\ProductOptionsRestApi\Processor\Mapper\ProductOptionMapperInterface $productOptionMapper
     * @param \Spryker\Glue\ProductOptionsRestApi\Processor\Sorter\ProductOptionSorterInterface $productOptionSorter
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ProductOptionsRestApiToProductStorageClientInterface $productStorageClient,
        ProductOptionsRestApiToProductOptionStorageClientInterface $productOptionStorageClient,
        ProductOptionsRestApiToGlossaryStorageClientInterface $glossaryStorageClient,
        ProductOptionMapperInterface $productOptionMapper,
        ProductOptionSorterInterface $productOptionSorter
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->productStorageClient = $productStorageClient;
        $this->productOptionStorageClient = $productOptionStorageClient;
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->productOptionMapper = $productOptionMapper;
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
        $productOptionRestResources = [];
        $productAbstractIds = $this->getProductAbstractIdsByProductAbstractSkus(
            $productAbstractSkus,
            $localeName
        );
        $restProductOptionAttributesTransfersByProductAbstractIds = $this->getRestProductOptionAttributesTransfersByProductAbstractIds(
            $productAbstractIds,
            $localeName
        );
        foreach ($restProductOptionAttributesTransfersByProductAbstractIds as $productAbstractSku => $restProductOptionAttributesTransfers) {
            $restProductOptionAttributesTransfers = $this->productOptionSorter->sortRestProductOptionAttributesTransfers(
                $restProductOptionAttributesTransfers,
                $sorts
            );
            $productOptionRestResources[$productAbstractSku] = $this->prepareRestResources(
                $restProductOptionAttributesTransfers,
                ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
                $productAbstractSku
            );
        }

        return $productOptionRestResources;
    }

    /**
     * @param string[] $productAbstractSkus
     * @param string $localeName
     *
     * @return int[]
     */
    protected function getProductAbstractIdsByProductAbstractSkus(array $productAbstractSkus, string $localeName): array
    {
        $productAbstractIdsByProductAbstractSkus = [];
        $productAbstractStorageDataItems = $this->productStorageClient->findBulkProductAbstractStorageDataByMapping(
            static::PRODUCT_ABSTRACT_MAPPING_TYPE,
            $productAbstractSkus,
            $localeName
        );
        foreach ($productAbstractStorageDataItems as $productAbstractStorageDataItem) {
            $productAbstractIdsByProductAbstractSkus[$productAbstractStorageDataItem[static::PRODUCT_ABSTRACT_MAPPING_TYPE]] =
                $productAbstractStorageDataItem[static::KEY_ID_PRODUCT_ABSTRACT];
        }

        return $productAbstractIdsByProductAbstractSkus;
    }

    /**
     * @param int[] $productAbstractIds
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestProductOptionAttributesTransfer[][]
     */
    protected function getRestProductOptionAttributesTransfersByProductAbstractIds(
        array $productAbstractIds,
        string $localeName
    ): array {
        $productAbstractOptionStorageTransfers = $this->productOptionStorageClient->getBulkProductOptions(
            $productAbstractIds
        );
        $translations = $this->glossaryStorageClient->translateBulk(
            $this->getGlossaryStorageKeys($productAbstractOptionStorageTransfers),
            $localeName
        );
        $restProductOptionAttributesTransfers = [];
        foreach ($productAbstractIds as $productAbstractSku => $idProductAbstract) {
            $productAbstractOptionStorageTransfer = $productAbstractOptionStorageTransfers[$idProductAbstract] ?? null;
            if (!$productAbstractOptionStorageTransfer) {
                continue;
            }

            $restProductOptionAttributesTransfers[$productAbstractSku] = $this->productOptionMapper
                ->mapProductAbstractOptionStorageTransferToRestProductOptionAttributesTransfers(
                    $productAbstractOptionStorageTransfer,
                    $translations
                );
        }

        return $restProductOptionAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer[] $productAbstractOptionStorageTransfers
     *
     * @return string[]
     */
    protected function getGlossaryStorageKeys(array $productAbstractOptionStorageTransfers): array
    {
        $glossaryStorageKeys = [];
        foreach ($productAbstractOptionStorageTransfers as $productAbstractOptionStorageTransfer) {
            foreach ($productAbstractOptionStorageTransfer->getProductOptionGroups() as $productOptionGroupStorageTransfer) {
                $glossaryStorageKeys[] = $productOptionGroupStorageTransfer->getName();

                foreach ($productOptionGroupStorageTransfer->getProductOptionValues() as $productOptionValueStorageTransfer) {
                    $glossaryStorageKeys[] = $productOptionValueStorageTransfer->getValue();
                }
            }
        }

        return array_unique($glossaryStorageKeys);
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
}
