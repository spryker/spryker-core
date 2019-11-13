<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Reader;

use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductOptionStorageClientInterface;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductOptionsRestApi\Processor\Mapper\ProductOptionMapperInterface;

class StorageReader implements StorageReaderInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';
    protected const KEY_ID_PRODUCT_CONCRETE = 'id_product_concrete';

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
     * @param \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductOptionStorageClientInterface $productOptionStorageClient
     * @param \Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \Spryker\Glue\ProductOptionsRestApi\Processor\Mapper\ProductOptionMapperInterface $productOptionMapper
     */
    public function __construct(
        ProductOptionsRestApiToProductStorageClientInterface $productStorageClient,
        ProductOptionsRestApiToProductOptionStorageClientInterface $productOptionStorageClient,
        ProductOptionsRestApiToGlossaryStorageClientInterface $glossaryStorageClient,
        ProductOptionMapperInterface $productOptionMapper
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->productOptionStorageClient = $productOptionStorageClient;
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->productOptionMapper = $productOptionMapper;
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
     * @param string[] $productConcreteSkus
     * @param string $localeName
     *
     * @return int[]
     */
    protected function getProductAbstractIdsByProductConcreteSkus(array $productConcreteSkus, string $localeName): array
    {
        $productAbstractIdsByProductConcreteSkus = [];
        $productConcreteStorageDataItems = $this->productStorageClient->getBulkProductConcreteStorageDataByMapping(
            static::PRODUCT_CONCRETE_MAPPING_TYPE,
            $productConcreteSkus,
            $localeName
        );
        foreach ($productConcreteStorageDataItems as $productConcreteStorageDataItem) {
            $productAbstractIdsByProductConcreteSkus[$productConcreteStorageDataItem[static::PRODUCT_CONCRETE_MAPPING_TYPE]] =
                $productConcreteStorageDataItem[static::KEY_ID_PRODUCT_ABSTRACT];
        }

        return $productAbstractIdsByProductConcreteSkus;
    }

    /**
     * @param string[] $productAbstractSkus
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestProductOptionAttributesTransfer[][]
     */
    public function getRestProductOptionAttributesTransfersByProductAbstractSkus(
        array $productAbstractSkus,
        string $localeName
    ): array {
        $productAbstractIds = $this->getProductAbstractIdsByProductAbstractSkus(
            $productAbstractSkus,
            $localeName
        );

        return $this->getRestProductOptionAttributesTransfersByProductAbstractIds($productAbstractIds, $localeName);
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
            $glossaryStorageKeys += $this->getGlossaryStorageKeysFromProductAbstractOptionStorageTransfer(
                $productAbstractOptionStorageTransfer
            );
        }

        return array_values($glossaryStorageKeys);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer
     *
     * @return string[]
     */
    protected function getGlossaryStorageKeysFromProductAbstractOptionStorageTransfer(
        ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer
    ): array {
        $glossaryStorageKeys = [];
        foreach ($productAbstractOptionStorageTransfer->getProductOptionGroups() as $productOptionGroupStorageTransfer) {
            $optionGroupName = $productOptionGroupStorageTransfer->getName();
            $glossaryStorageKeys[$optionGroupName] = $optionGroupName;

            foreach ($productOptionGroupStorageTransfer->getProductOptionValues() as $productOptionValueStorageTransfer) {
                $optionValueName = $productOptionValueStorageTransfer->getValue();
                $glossaryStorageKeys[$optionValueName] = $optionValueName;
            }
        }

        return $glossaryStorageKeys;
    }

    /**
     * @param string[] $productConcreteSkus
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestProductOptionAttributesTransfer[][]
     */
    public function getRestProductOptionAttributesTransfersByProductConcreteSkus(
        array $productConcreteSkus,
        string $localeName
    ): array {
        $productAbstractIds = $this->getProductAbstractIdsByProductConcreteSkus(
            $productConcreteSkus,
            $localeName
        );

        return $this->getRestProductOptionAttributesTransfersByProductAbstractIds($productAbstractIds, $localeName);
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
        foreach ($productAbstractIds as $productSku => $idProductAbstract) {
            $productAbstractOptionStorageTransfer = $productAbstractOptionStorageTransfers[$idProductAbstract] ?? null;
            if (!$productAbstractOptionStorageTransfer) {
                continue;
            }

            $restProductOptionAttributesTransfers[$productSku] = $this->productOptionMapper
                ->mapProductAbstractOptionStorageTransferToRestProductOptionAttributesTransfers(
                    $productAbstractOptionStorageTransfer,
                    $translations
                );
        }

        return $restProductOptionAttributesTransfers;
    }
}
