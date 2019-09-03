<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer;
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

        return array_map(function ($productLabelIds) use ($localeName) {
            return $this->findSortedProductLabelsInDictionary($productLabelIds, $localeName);
        }, $productLabelIdsByAbstractProductIds);
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

        $storageKey = $this->getProductLabelStorageKeyByIdAbstractProduct($idProductAbstract);
        $storageData = $this->storageClient->get($storageKey);

        if (!$storageData) {
            return [];
        }

        return $this->mapStorageDataToProductLabelIds($storageData);
    }

    /**
     * @param int[] $abstractProductIds
     *
     * @return int[][]
     */
    protected function getProductLabelIdsByAbstractProductIds(array $abstractProductIds): array
    {
        $storageKeys = [];

        foreach ($abstractProductIds as $idProductAbstract) {
            $storageKeys[$idProductAbstract] = $this->getProductLabelStorageKeyByIdAbstractProduct($idProductAbstract);
        }

        $storageData = array_map(function ($storageData) {
            return $this->utilEncodingService->decodeJson($storageData, true);
        }, $this->storageClient->getMulti($storageKeys));
        $productLabelIds = [];

        foreach ($storageData as $storageDataItem) {
            $productLabelIds[$storageDataItem[static::KEY_ID_PRODUCT_ABSTRACT]] =
                $this->mapStorageDataToProductLabelIds($storageDataItem);
        }

        return array_filter($productLabelIds);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return string
     */
    protected function getProductLabelStorageKeyByIdAbstractProduct(int $idProductAbstract): string
    {
        return $this->synchronizationService
            ->getStorageKeyBuilder(SharedProductLabelStorageConfig::PRODUCT_ABSTRACT_LABEL_RESOURCE_NAME)
            ->generateKey(
                (new SynchronizationDataTransfer())
                    ->setReference((string)$idProductAbstract)
            );
    }

    /**
     * @param array $storageData
     *
     * @return array
     */
    protected function mapStorageDataToProductLabelIds(array $storageData): array
    {
        return (new ProductAbstractLabelStorageTransfer())
            ->fromArray($storageData, true)
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
