<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer;
use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\Kernel\Locator;
use Spryker\Client\ProductLabelStorage\Dependency\Client\ProductLabelStorageToStorageClientInterface;
use Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToUtilEncodingServiceInterface;
use Spryker\Client\ProductLabelStorage\ProductLabelStorageConfig;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductLabelStorage\ProductLabelStorageConfig as SharedProductLabelStorageConfig;

class ProductAbstractLabelReader implements ProductAbstractLabelReaderInterface
{
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var \Spryker\Client\ProductLabelStorage\Dependency\Client\ProductLabelStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Spryker\Client\ProductLabelStorage\Storage\LabelDictionaryReaderInterface
     */
    protected $labelDictionaryReader;

    /**
     * @var \Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Client\ProductLabelStorage\Dependency\Client\ProductLabelStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductLabelStorage\Storage\LabelDictionaryReaderInterface $labelDictionaryReader
     * @param \Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductLabelStorageToStorageClientInterface $storageClient,
        ProductLabelStorageToSynchronizationServiceInterface $synchronizationService,
        LabelDictionaryReaderInterface $labelDictionaryReader,
        ProductLabelStorageToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->storageClient = $storageClient;
        $this->labelDictionaryReader = $labelDictionaryReader;
        $this->synchronizationService = $synchronizationService;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabelsByIdProductAbstract($idProductAbstract, $localeName)
    {
        $idsProductLabel = $this->findIdsProductLabelByIdAbstractProduct($idProductAbstract);

        if (!$idsProductLabel) {
            return [];
        }

        return $this->findSortedProductLabelsInDictionary($idsProductLabel, $localeName);
    }

    /**
     * @param int[] $productAbstractIds
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[][]
     */
    public function getLabelsByProductAbstractIds(array $productAbstractIds, string $localeName): array
    {
        $productLabelIdsByAbstractProductIds = $this->getProductLabelIdsByAbstractProductIds($productAbstractIds);

        if (!$productLabelIdsByAbstractProductIds) {
            return [];
        }

        $productLabelIds = array_unique(array_merge(...$productLabelIdsByAbstractProductIds));
        $productLabelDictionaryItemTransfers = $this->setProductLabelDictionaryItemTransfersProductLabelIdKeys(
            $this->findSortedProductLabelsInDictionary($productLabelIds, $localeName)
        );
        $productLabelDictionaryItemTransfersByAbstractProductIds = [];

        foreach ($productLabelIdsByAbstractProductIds as $productAbstractId => $productLabelIds) {
            $productLabelDictionaryItemTransfersByAbstractProductIds[$productAbstractId] = array_intersect_key(
                $productLabelDictionaryItemTransfers,
                array_flip($productLabelIds)
            );
        }

        return $productLabelDictionaryItemTransfersByAbstractProductIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[] $productLabelDictionaryItemTransfers
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    protected function setProductLabelDictionaryItemTransfersProductLabelIdKeys(array $productLabelDictionaryItemTransfers): array
    {
        $productLabelIds = array_map(function (ProductLabelDictionaryItemTransfer $productLabelDictionaryItemTransfer) {
            return $productLabelDictionaryItemTransfer->getIdProductLabel();
        }, $productLabelDictionaryItemTransfers);

        return array_combine(
            $productLabelIds,
            $productLabelDictionaryItemTransfers
        );
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    protected function findIdsProductLabelByIdAbstractProduct($idProductAbstract)
    {
        if (ProductLabelStorageConfig::isCollectorCompatibilityMode()) {
            $clientLocatorClassName = Locator::class;
            /** @var \Spryker\Client\ProductLabel\ProductLabelClientInterface $productLabelClient */
            $productLabelClient = $clientLocatorClassName::getInstance()->productLabel()->client();
            $collectorData = $productLabelClient->findLabelsByIdProductAbstract($idProductAbstract, Store::getInstance()->getCurrentLocale());

            $labelIds = [];
            foreach ($collectorData as $storageProductLabelTransfer) {
                $labelIds[] = $storageProductLabelTransfer->getIdProductLabel();
            }

            return $labelIds;
        }

        $storageKey = $this->generateProductLabelStorageKey($idProductAbstract);
        $storageDataItem = $this->storageClient->get($storageKey);

        if (!$storageDataItem) {
            return [];
        }

        return $this->getProductLabelIdsFromProductLabelStorageDataItem($storageDataItem);
    }

    /**
     * @param int[] $abstractProductIds
     *
     * @return int[][]
     */
    protected function getProductLabelIdsByAbstractProductIds(array $abstractProductIds): array
    {
        $storageKeys = $this->generateProductLabelStorageKeys($abstractProductIds);
        $storageDataItems = $this->getProductLabelStorageDataItemsByProductLabelStorageKeys($storageKeys);

        return $this->getProductLabelIdsGroupedByIdProductAbstract($storageDataItems);
    }

    /**
     * @param int[] $abstractProductIds
     *
     * @return string[]
     */
    protected function generateProductLabelStorageKeys(array $abstractProductIds): array
    {
        $storageKeys = [];

        foreach ($abstractProductIds as $idProductAbstract) {
            $storageKeys[$idProductAbstract] = $this->generateProductLabelStorageKey($idProductAbstract);
        }

        return $storageKeys;
    }

    /**
     * @param string[] $storageKeys
     *
     * @return array
     */
    protected function getProductLabelStorageDataItemsByProductLabelStorageKeys(array $storageKeys): array
    {
        $storageData = array_filter($this->storageClient->getMulti($storageKeys));

        return array_map(function (string $productLabelStorageEncodedData) {
            return $this->utilEncodingService->decodeJson($productLabelStorageEncodedData, true);
        }, $storageData);
    }

    /**
     * @param array $storageDataItems
     *
     * @return int[][]
     */
    protected function getProductLabelIdsGroupedByIdProductAbstract(array $storageDataItems): array
    {
        $productLabelIds = [];

        foreach ($storageDataItems as $storageDataItem) {
            $productLabelIds[$storageDataItem[static::KEY_ID_PRODUCT_ABSTRACT]] =
                $this->getProductLabelIdsFromProductLabelStorageDataItem($storageDataItem);
        }

        return array_filter($productLabelIds);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return string
     */
    protected function generateProductLabelStorageKey(int $idProductAbstract): string
    {
        return $this->synchronizationService
            ->getStorageKeyBuilder(SharedProductLabelStorageConfig::PRODUCT_ABSTRACT_LABEL_RESOURCE_NAME)
            ->generateKey(
                (new SynchronizationDataTransfer())
                    ->setReference((string)$idProductAbstract)
            );
    }

    /**
     * @param array $storageDataItem
     *
     * @return array
     */
    protected function getProductLabelIdsFromProductLabelStorageDataItem(array $storageDataItem): array
    {
        return (new ProductAbstractLabelStorageTransfer())
            ->fromArray($storageDataItem, true)
            ->getProductLabelIds();
    }

    /**
     * @param int[] $productLabelIds
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    protected function findSortedProductLabelsInDictionary($productLabelIds, $localeName)
    {
        return $this->labelDictionaryReader->findSortedLabelsByIdsProductLabel($productLabelIds, $localeName);
    }
}
