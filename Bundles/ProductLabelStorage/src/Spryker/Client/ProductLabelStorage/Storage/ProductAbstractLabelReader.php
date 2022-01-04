<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage\Storage;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\Kernel\Locator;
use Spryker\Client\ProductLabelStorage\Dependency\Client\ProductLabelStorageToLocaleClientInterface;
use Spryker\Client\ProductLabelStorage\Dependency\Client\ProductLabelStorageToStorageClientInterface;
use Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToSynchronizationServiceInterface;
use Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToUtilEncodingServiceInterface;
use Spryker\Client\ProductLabelStorage\ProductLabelStorageConfig;
use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;
use Spryker\Shared\ProductLabelStorage\ProductLabelStorageConfig as SharedProductLabelStorageConfig;

class ProductAbstractLabelReader implements ProductAbstractLabelReaderInterface
{
    /**
     * @var string
     */
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var string
     */
    protected const KEY_PRODUCT_LABEL_IDS = 'product_label_ids';

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
     * @var \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface|null
     */
    protected static $storageKeyBuilder;

    /**
     * @var \Spryker\Client\ProductLabelStorage\Dependency\Client\ProductLabelStorageToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @param \Spryker\Client\ProductLabelStorage\Dependency\Client\ProductLabelStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductLabelStorage\Storage\LabelDictionaryReaderInterface $labelDictionaryReader
     * @param \Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Client\ProductLabelStorage\Dependency\Client\ProductLabelStorageToLocaleClientInterface $localeClient
     */
    public function __construct(
        ProductLabelStorageToStorageClientInterface $storageClient,
        ProductLabelStorageToSynchronizationServiceInterface $synchronizationService,
        LabelDictionaryReaderInterface $labelDictionaryReader,
        ProductLabelStorageToUtilEncodingServiceInterface $utilEncodingService,
        ProductLabelStorageToLocaleClientInterface $localeClient
    ) {
        $this->storageClient = $storageClient;
        $this->labelDictionaryReader = $labelDictionaryReader;
        $this->synchronizationService = $synchronizationService;
        $this->utilEncodingService = $utilEncodingService;
        $this->localeClient = $localeClient;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>
     */
    public function findLabelsByIdProductAbstract($idProductAbstract, $localeName, string $storeName)
    {
        $productLabelIds = $this->findIdsProductLabelByIdAbstractProduct($idProductAbstract);

        if (!$productLabelIds) {
            return [];
        }

        return $this->findSortedProductLabelsInDictionary($productLabelIds, $localeName, $storeName);
    }

    /**
     * @param array<int> $productAbstractIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>>
     */
    public function getProductLabelsByProductAbstractIds(array $productAbstractIds, string $localeName, string $storeName): array
    {
        $productLabelIdsByProductAbstractIds = $this->getProductLabelIdsByProductAbstractIds($productAbstractIds);

        if (!$productLabelIdsByProductAbstractIds) {
            return [];
        }

        return $this->getProductLabelDictionaryItemTransfersGroupedByProductAbstractIds(
            $productLabelIdsByProductAbstractIds,
            $localeName,
            $storeName,
        );
    }

    /**
     * @param array<array<int>> $productLabelIdsByProductAbstractIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>>
     */
    protected function getProductLabelDictionaryItemTransfersGroupedByProductAbstractIds(
        array $productLabelIdsByProductAbstractIds,
        string $localeName,
        string $storeName
    ): array {
        $uniqueProductLabelIds = array_unique(array_merge(...$productLabelIdsByProductAbstractIds));
        $productLabelDictionaryItemTransfers = $this->getProductLabelDictionaryItemTransfersGroupedById(
            $this->findSortedProductLabelsInDictionary($uniqueProductLabelIds, $localeName, $storeName),
        );

        $productLabelDictionaryItemTransfersByProductAbstractIds = [];
        foreach ($productLabelIdsByProductAbstractIds as $productAbstractId => $productLabelIds) {
            $productLabelDictionaryItemTransfersByProductAbstractIds[$productAbstractId] = array_intersect_key(
                $productLabelDictionaryItemTransfers,
                array_flip($productLabelIds),
            );
        }

        return $productLabelDictionaryItemTransfersByProductAbstractIds;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer> $productLabelDictionaryItemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>
     */
    protected function getProductLabelDictionaryItemTransfersGroupedById(array $productLabelDictionaryItemTransfers): array
    {
        $indexedProductLabelDictionaryItemTransfers = [];

        foreach ($productLabelDictionaryItemTransfers as $productLabelDictionaryItemTransfer) {
            $indexedProductLabelDictionaryItemTransfers[$productLabelDictionaryItemTransfer->getIdProductLabel()]
                = $productLabelDictionaryItemTransfer;
        }

        return $indexedProductLabelDictionaryItemTransfers;
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
            $collectorData = $productLabelClient->findLabelsByIdProductAbstract(
                $idProductAbstract,
                $this->localeClient->getCurrentLocale(),
            );

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

        return $storageDataItem[static::KEY_PRODUCT_LABEL_IDS];
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<array<int>>
     */
    protected function getProductLabelIdsByProductAbstractIds(array $productAbstractIds): array
    {
        $storageKeys = $this->generateProductLabelStorageKeys($productAbstractIds);
        $storageDataItems = $this->getProductLabelStorageDataItemsByProductLabelStorageKeys($storageKeys);

        return $this->getProductLabelIdsGroupedByIdProductAbstract($storageDataItems);
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<string>
     */
    protected function generateProductLabelStorageKeys(array $productAbstractIds): array
    {
        $storageKeys = [];

        foreach ($productAbstractIds as $idProductAbstract) {
            $storageKeys[$idProductAbstract] = $this->generateProductLabelStorageKey($idProductAbstract);
        }

        return $storageKeys;
    }

    /**
     * @param array<string> $storageKeys
     *
     * @return array
     */
    protected function getProductLabelStorageDataItemsByProductLabelStorageKeys(array $storageKeys): array
    {
        $storageData = $this->storageClient->getMulti($storageKeys);

        $decodedStorageData = [];
        foreach ($storageData as $storageDataItem) {
            $decodedStorageDataItem = $this->utilEncodingService->decodeJson($storageDataItem, true);

            if (!$decodedStorageDataItem) {
                continue;
            }

            $decodedStorageData[] = $decodedStorageDataItem;
        }

        return $decodedStorageData;
    }

    /**
     * @param array $storageDataItems
     *
     * @return array<array<int>>
     */
    protected function getProductLabelIdsGroupedByIdProductAbstract(array $storageDataItems): array
    {
        $productLabelIds = [];

        foreach ($storageDataItems as $storageDataItem) {
            $productLabelIds[$storageDataItem[static::KEY_ID_PRODUCT_ABSTRACT]] =
                $storageDataItem[static::KEY_PRODUCT_LABEL_IDS];
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
        return $this->getStorageKeyBuilder()
            ->generateKey(
                (new SynchronizationDataTransfer())
                    ->setReference((string)$idProductAbstract),
            );
    }

    /**
     * @return \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface
     */
    protected function getStorageKeyBuilder(): SynchronizationKeyGeneratorPluginInterface
    {
        if (static::$storageKeyBuilder === null) {
            static::$storageKeyBuilder = $this->synchronizationService->getStorageKeyBuilder(SharedProductLabelStorageConfig::PRODUCT_ABSTRACT_LABEL_RESOURCE_NAME);
        }

        return static::$storageKeyBuilder;
    }

    /**
     * @param array<int> $productLabelIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>
     */
    protected function findSortedProductLabelsInDictionary($productLabelIds, $localeName, string $storeName)
    {
        return $this->labelDictionaryReader->findSortedLabelsByIdsProductLabel($productLabelIds, $localeName, $storeName);
    }
}
