<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Storage;

use ArrayObject;
use Generated\Shared\Transfer\ProductLabelCriteriaTransfer;
use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;
use Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\ProductLabelStorage\Business\Mapper\ProductLabelDictionaryItemMapper;
use Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToProductLabelBridge;
use Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManager;
use Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageRepository;

class ProductLabelDictionaryStorageWriter implements ProductLabelDictionaryStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageRepository
     */
    protected $productLabelStorageRepository;

    /**
     * @var \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManager
     */
    protected $productLabelStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToProductLabelBridge
     */
    protected $productLabelFacade;

    /**
     * @var \Spryker\Zed\ProductLabelStorage\Business\Mapper\ProductLabelDictionaryItemMapper
     */
    protected $productLabelDictionaryItemMapper;

    /**
     * @param \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageRepository $productLabelStorageRepository
     * @param \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManager $productLabelStorageEntityManager
     * @param \Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToProductLabelBridge $productLabelFacade
     * @param \Spryker\Zed\ProductLabelStorage\Business\Mapper\ProductLabelDictionaryItemMapper $productLabelDictionaryItemMapper
     */
    public function __construct(
        ProductLabelStorageRepository $productLabelStorageRepository,
        ProductLabelStorageEntityManager $productLabelStorageEntityManager,
        ProductLabelStorageToProductLabelBridge $productLabelFacade,
        ProductLabelDictionaryItemMapper $productLabelDictionaryItemMapper
    ) {
        $this->productLabelStorageRepository = $productLabelStorageRepository;
        $this->productLabelStorageEntityManager = $productLabelStorageEntityManager;
        $this->productLabelFacade = $productLabelFacade;
        $this->productLabelDictionaryItemMapper = $productLabelDictionaryItemMapper;
    }

    /**
     * @inheritDoc
     */
    public function publish(array $productLabelIds = [])
    {
        $productLabelCriteriaTransfer = (new ProductLabelCriteriaTransfer())
            ->setIdProductLabels($productLabelIds);

        $productLabelTransfers = $this->productLabelFacade
            ->getActiveLabelsByCriteria($productLabelCriteriaTransfer);

        $productLabelDictionaryItems = $this->createProductLabelDictionaryItems($productLabelTransfers);
        $productLabelDictionaryStorageItems = $this->productLabelStorageRepository->getProductLabelDictionaryStorageTransfers();

        if (!$productLabelDictionaryItems) {
            $this->deleteStorageData($productLabelIds, $productLabelDictionaryStorageItems);

            return;
        }

        $this->storeData($productLabelDictionaryItems, $productLabelDictionaryStorageItems);
    }

    /**
     * @inheritDoc
     */
    public function unpublish(array $productLabelIds = [])
    {
        $productLabelDictionaryStorageItems = $this->productLabelStorageRepository->getProductLabelDictionaryStorageTransfers();

        $this->deleteStorageData($productLabelIds, $productLabelDictionaryStorageItems);
    }

    /**
     * @param int[] $productLabelIds
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer[][] $productLabelDictionaryStorageItems
     *
     * @return void
     */
    protected function deleteStorageData(array $productLabelIds, array $productLabelDictionaryStorageItems)
    {
        foreach ($productLabelDictionaryStorageItems as $productLabelDictionaryStorage) {
            $storageItems = new ArrayObject();

            /** @var \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer $productLabelDictionaryStorageItem */
            foreach ($productLabelDictionaryStorage->getItems() as $productLabelDictionaryStorageItem) {
                if (!in_array($productLabelDictionaryStorageItem->getIdProductLabel(), $productLabelIds)) {
                    $storageItems->append($productLabelDictionaryStorageItem);
                }
            }

            if (!$storageItems->count()) {
                $this->productLabelStorageEntityManager->deleteProductLabelDictionaryStorageById(
                    $productLabelDictionaryStorage->getIdProductLabelDictionaryStorage()
                );

                continue;
            }

            $this->productLabelStorageEntityManager->updateProductLabelDictionaryStorage(
                $productLabelDictionaryStorage->setItems($storageItems)
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer[] $productLabelTransfers
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[][]
     */
    protected function createProductLabelDictionaryItems(array $productLabelTransfers): array
    {
        $productLabelDictionaryItems = [];

        foreach ($productLabelTransfers as $productLabelTransfer) {
            $this->fillProductLabelDictionaryItems($productLabelDictionaryItems, $productLabelTransfer);
        }

        return $productLabelDictionaryItems;
    }

    /**
     * @param array $productLabelDictionaryItemTransfers
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    protected function fillProductLabelDictionaryItems(
        array &$productLabelDictionaryItemTransfers,
        ProductLabelTransfer $productLabelTransfer
    ): void {
        foreach ($productLabelTransfer->getStoreRelation()->getStores() as $store) {
            $productLabelDictionaryItemTransfers = array_merge(
                $productLabelDictionaryItemTransfers,
                $this->productLabelDictionaryItemMapper
                    ->mapProductLabelTransferToProductLabelDictionaryItemTransfersForStoreByLocale(
                        $productLabelTransfer,
                        $productLabelDictionaryItemTransfers,
                        $store->getName()
                    )
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[][] $productLabelDictionaryItems
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer[] $productLabelDictionaryStorageItems
     *
     * @return void
     */
    protected function storeData(array $productLabelDictionaryItems, array $productLabelDictionaryStorageItems)
    {
        $productLabelDictionaryStorageCollectionForPersist = [];
        foreach ($productLabelDictionaryItems as $storeName => $productLabelDictionaryLocaleItems) {
            /** @var \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[] $productLabelDictionaryItems */
            foreach ($productLabelDictionaryLocaleItems as $localeName => $productLabelDictionaryItems) {
                $foundProductLabelDictionaryStorageItem = $this->extractProductLabelDictionaryItemByStoreAndLocale(
                    $productLabelDictionaryStorageItems,
                    $storeName,
                    $localeName
                );

                if (!$foundProductLabelDictionaryStorageItem) {
                    $productLabelDictionaryStorageCollectionForPersist[] = (new ProductLabelDictionaryStorageTransfer())
                        ->setStore($storeName)
                        ->setLocale($localeName)
                        ->setItems(
                            new ArrayObject(
                                array_map(function (ProductLabelDictionaryItemTransfer $productLabelDictionaryItemTransfer) {
                                    return $productLabelDictionaryItemTransfer->modifiedToArray();
                                }, $productLabelDictionaryItems)
                            )
                        );

                    continue;
                }

                $productLabelDictionaryStorageCollectionForPersist[] = $foundProductLabelDictionaryStorageItem;

                $this->updateProductLabelDictionaryItemInCollectionForPersist(
                    $productLabelDictionaryStorageCollectionForPersist,
                    $storeName,
                    $localeName,
                    $productLabelDictionaryItems
                );
            }
        }
        $this->batchPersistProductLabelDictionaries($productLabelDictionaryStorageCollectionForPersist);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer[] $productLabelDictionaryStorageCollectionForPersist
     *
     * @return void
     */
    protected function batchPersistProductLabelDictionaries(array $productLabelDictionaryStorageCollectionForPersist)
    {
        foreach ($productLabelDictionaryStorageCollectionForPersist as $productLabelDictionaryStorageItem) {
            $productLabelDictionaryStorageItemId = $productLabelDictionaryStorageItem->getIdProductLabelDictionaryStorage();

            if (!$productLabelDictionaryStorageItemId) {
                $this->productLabelStorageEntityManager->createProductLabelDictionaryStorage($productLabelDictionaryStorageItem);

                continue;
            }

            if ($productLabelDictionaryStorageItemId && $productLabelDictionaryStorageItem->getItems()->count() == 0) {
                $this->productLabelStorageEntityManager->deleteProductLabelDictionaryStorageById($productLabelDictionaryStorageItem->getIdProductLabelDictionaryStorage());

                continue;
            }

            $this->productLabelStorageEntityManager->updateProductLabelDictionaryStorage($productLabelDictionaryStorageItem);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer[] $batchProductLabelDictionaryStorageTransferCollectionForPersist
     * @param string $storeName
     * @param string $localeName
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[] $productLabelDictionaryItemsForCheck
     *
     * @return void
     */
    protected function updateProductLabelDictionaryItemInCollectionForPersist(
        array &$batchProductLabelDictionaryStorageTransferCollectionForPersist,
        string $storeName,
        string $localeName,
        array $productLabelDictionaryItemsForCheck
    ) {
        foreach ($batchProductLabelDictionaryStorageTransferCollectionForPersist as $productLabelDictionaryStorageTransfer) {
            if (
                $productLabelDictionaryStorageTransfer->getStore() == $storeName
                && $productLabelDictionaryStorageTransfer->getLocale() == $localeName
            ) {
                foreach ($productLabelDictionaryItemsForCheck as $productLabelDictionaryItemForCheck) {
                    foreach ($productLabelDictionaryStorageTransfer->getItems() as $offsetKey => $productLabelDictionaryItemTransfer) {
                        if (
                            $this->replaceProductLabelDictionaryItemIfNeed(
                                $productLabelDictionaryStorageTransfer,
                                $offsetKey,
                                $productLabelDictionaryItemTransfer,
                                $productLabelDictionaryItemForCheck
                            )
                        ) {
                            break;
                        }

                        if (
                            $this->appendProductLabelDictionaryItemTransfersIfNeed(
                                $productLabelDictionaryStorageTransfer,
                                $productLabelDictionaryItemForCheck
                            )
                        ) {
                            break;
                        }
                    }
                }
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer[] $productLabelDictionaryStorageItems
     * @param string $storeName
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer|null
     */
    protected function extractProductLabelDictionaryItemByStoreAndLocale(array $productLabelDictionaryStorageItems, string $storeName, string $localeName): ?ProductLabelDictionaryStorageTransfer
    {
        foreach ($productLabelDictionaryStorageItems as $productLabelDictionaryStorageItem) {
            if (
                $productLabelDictionaryStorageItem->getStore() == $storeName
                && $productLabelDictionaryStorageItem->getLocale() == $localeName
            ) {
                return $productLabelDictionaryStorageItem;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer
     * @param int $offsetKey
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer $productLabelDictionaryItemTransfer
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer $productLabelDictionaryItemForCheck
     *
     * @return bool
     */
    protected function replaceProductLabelDictionaryItemIfNeed(
        ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer,
        int $offsetKey,
        ProductLabelDictionaryItemTransfer $productLabelDictionaryItemTransfer,
        ProductLabelDictionaryItemTransfer $productLabelDictionaryItemForCheck
    ): bool {
        if ($productLabelDictionaryItemTransfer->getIdProductLabel() == $productLabelDictionaryItemForCheck->getIdProductLabel()) {
            $productLabelDictionaryStorageTransfer->getItems()->offsetUnset($offsetKey);
            $productLabelDictionaryStorageTransfer->getItems()->offsetSet($offsetKey, $productLabelDictionaryItemForCheck);

            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer $productLabelDictionaryItemForCheck
     *
     * @return bool
     */
    protected function appendProductLabelDictionaryItemTransfersIfNeed(
        ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer,
        ProductLabelDictionaryItemTransfer $productLabelDictionaryItemForCheck
    ): bool {
        foreach ($productLabelDictionaryStorageTransfer->getItems() as $productLabelDictionaryItemTransfer) {
            if ($productLabelDictionaryItemTransfer->getIdProductLabel() == $productLabelDictionaryItemForCheck->getIdProductLabel()) {
                return false;
            }
        }
        $productLabelDictionaryStorageTransfer->getItems()->append($productLabelDictionaryItemForCheck);

        return true;
    }
}
