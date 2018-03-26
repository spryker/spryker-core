<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage\Storage;

use Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\ProductLabelStorage\Dependency\Client\ProductLabelStorageToStorageClientInterface;
use Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToSynchronizationServiceInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductLabelStorage\ProductLabelStorageConfig;

class ProductAbstractLabelReader implements ProductAbstractLabelReaderInterface
{
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
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Client\ProductLabelStorage\Dependency\Client\ProductLabelStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\ProductLabelStorage\Storage\LabelDictionaryReaderInterface $labelDictionaryReader
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(
        ProductLabelStorageToStorageClientInterface $storageClient,
        ProductLabelStorageToSynchronizationServiceInterface $synchronizationService,
        LabelDictionaryReaderInterface $labelDictionaryReader,
        Store $store
    ) {
        $this->storageClient = $storageClient;
        $this->labelDictionaryReader = $labelDictionaryReader;
        $this->synchronizationService = $synchronizationService;
        $this->store = $store;
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
     * @param int $idProductAbstract
     *
     * @return array
     */
    protected function findIdsProductLabelByIdAbstractProduct($idProductAbstract)
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();
        $synchronizationDataTransfer
            ->setStore($this->store->getStoreName())
            ->setReference($idProductAbstract);

        $storageKey = $this->synchronizationService
            ->getStorageKeyBuilder(ProductLabelStorageConfig::PRODUCT_ABSTRACT_LABEL_RESOURCE_NAME)
            ->generateKey($synchronizationDataTransfer);

        $storageData = $this->storageClient->get($storageKey);

        if (!$storageData) {
            return [];
        }

        $productAbstractLabelStorageTransfer = new ProductAbstractLabelStorageTransfer();
        $productAbstractLabelStorageTransfer->fromArray($storageData, true);

        return $productAbstractLabelStorageTransfer->getProductLabelIds();
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
