<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Reader;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductOptionStorageClientInterface;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Mapper\ProductOptionMapperInterface;
use Spryker\Glue\ProductOptionsRestApi\ProductOptionsRestApiConfig;

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
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductOptionStorageClientInterface $productOptionStorageClient
     * @param \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \Spryker\Glue\ProductOptionsRestApi\Processor\Mapper\ProductOptionMapperInterface $productOptionMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ProductOptionsRestApiToProductStorageClientInterface $productStorageClient,
        ProductOptionsRestApiToProductOptionStorageClientInterface $productOptionStorageClient,
        ProductOptionsRestApiToGlossaryStorageClientInterface $glossaryStorageClient,
        ProductOptionMapperInterface $productOptionMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->productStorageClient = $productStorageClient;
        $this->productOptionStorageClient = $productOptionStorageClient;
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->productOptionMapper = $productOptionMapper;
    }

    /**
     * @param string[] $productAbstractSkus
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    public function getByProductAbstractSkus(array $productAbstractSkus, string $localeName): array
    {
        $productAbstractIds = $this->getProductAbstractIdsByProductAbstractSkus(
            $productAbstractSkus,
            $localeName
        );
        $restProductOptionsAttributesTransfersByProductAbstractSkus = $this->getRestProductOptionsAttributesTransfersByProductAbstractIds(
            $productAbstractIds,
            $localeName
        );
        $restResources = [];

        foreach ($restProductOptionsAttributesTransfersByProductAbstractSkus as $productAbstractSku => $restProductOptionsAttributesTransfers) {
            $restResources[$productAbstractSku] = $this->prepareRestResourceCollection(
                $restProductOptionsAttributesTransfers
            );
        }

        return $restResources;
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
     * @return \Generated\Shared\Transfer\RestProductOptionsAttributesTransfer[][]
     */
    protected function getRestProductOptionsAttributesTransfersByProductAbstractIds(
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
        $restProductOptionsAttributesTransfers = [];

        foreach ($productAbstractIds as $productAbstractSku => $idProductAbstract) {
            $productAbstractOptionStorageTransfer = $productAbstractOptionStorageTransfers[$idProductAbstract] ?? null;
            if (!$productAbstractOptionStorageTransfer) {
                continue;
            }

            $restProductOptionsAttributesTransfers[$productAbstractSku] = $this->productOptionMapper
                ->mapProductAbstractOptionStorageTransferToRestProductOptionsAttributesTransfers(
                    $productAbstractOptionStorageTransfer,
                    $translations
                );
        }

        return $restProductOptionsAttributesTransfers;
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
     * @param \Generated\Shared\Transfer\RestProductOptionsAttributesTransfer[] $restProductOptionsAttributesTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    protected function prepareRestResourceCollection(array $restProductOptionsAttributesTransfers): array
    {
        $restResources = [];

        foreach ($restProductOptionsAttributesTransfers as $restProductOptionsAttributesTransfer) {
            $restResources[] = $this->restResourceBuilder->createRestResource(
                ProductOptionsRestApiConfig::RESOURCE_PRODUCT_OPTIONS,
                $restProductOptionsAttributesTransfer->getSku(),
                $restProductOptionsAttributesTransfer
            );
        }

        return $restResources;
    }
}
