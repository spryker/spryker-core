<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer;
use Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductAbstractReaderInterface;
use Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductCategoryStorageReaderInterface;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToStoreFacadeInterface;
use Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageEntityManagerInterface;
use Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface;
use Spryker\Zed\ProductCategoryStorage\ProductCategoryStorageConfig;

class ProductCategoryStorageWriter implements ProductCategoryStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface
     */
    protected $productCategoryStorageRepository;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageEntityManagerInterface
     */
    protected $productCategoryStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductAbstractReaderInterface
     */
    protected $productAbstractReader;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductCategoryStorageReaderInterface
     */
    protected $productCategoryStorageReader;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\ProductCategoryStorageConfig
     */
    protected $productCategoryStorageConfig;

    /**
     * @param \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageRepositoryInterface $productCategoryStorageRepository
     * @param \Spryker\Zed\ProductCategoryStorage\Persistence\ProductCategoryStorageEntityManagerInterface $productCategoryStorageEntityManager
     * @param \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductAbstractReaderInterface $productAbstractReader
     * @param \Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductCategoryStorageReaderInterface $productCategoryStorageReader
     * @param \Spryker\Zed\ProductCategoryStorage\ProductCategoryStorageConfig $productCategoryStorageConfig
     */
    public function __construct(
        ProductCategoryStorageRepositoryInterface $productCategoryStorageRepository,
        ProductCategoryStorageEntityManagerInterface $productCategoryStorageEntityManager,
        ProductCategoryStorageToStoreFacadeInterface $storeFacade,
        ProductAbstractReaderInterface $productAbstractReader,
        ProductCategoryStorageReaderInterface $productCategoryStorageReader,
        ProductCategoryStorageConfig $productCategoryStorageConfig
    ) {
        $this->productCategoryStorageRepository = $productCategoryStorageRepository;
        $this->productCategoryStorageEntityManager = $productCategoryStorageEntityManager;
        $this->storeFacade = $storeFacade;
        $this->productAbstractReader = $productAbstractReader;
        $this->productCategoryStorageReader = $productCategoryStorageReader;
        $this->productCategoryStorageConfig = $productCategoryStorageConfig;
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function writeCollection(array $productAbstractIds): void
    {
        $writeCollectionBatchSize = $this->productCategoryStorageConfig->getWriteCollectionBatchSize();
        $productAbstractIdsBatchCollection = array_chunk($productAbstractIds, $writeCollectionBatchSize);

        foreach ($productAbstractIdsBatchCollection as $productAbstractIdsBatch) {
            $this->writeCollectionByBatch($productAbstractIdsBatch);
        }
    }

    /**
     * @param array<int> $categoryIds
     * @param bool $allowEmptyCategories
     *
     * @return void
     */
    public function writeCollectionByRelatedCategories(array $categoryIds, bool $allowEmptyCategories): void
    {
        if (!$allowEmptyCategories && $categoryIds === []) {
            return;
        }

        $relatedCategoryIds = $this->productAbstractReader->getRelatedCategoryIds($categoryIds);
        $productAbstractIds = $this->productAbstractReader
            ->getProductAbstractIdsByCategoryIds($relatedCategoryIds);

        $this->writeCollection($productAbstractIds);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer> $productAbstractLocalizedAttributesTransfers
     * @param array<array<array<\Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer>>> $productAbstractCategoryStorageTransfers
     * @param array<array<\Generated\Shared\Transfer\ProductCategoryTransfer>> $productCategoryTransfers
     *
     * @return void
     */
    protected function storeData(
        array $productAbstractLocalizedAttributesTransfers,
        array $productAbstractCategoryStorageTransfers,
        array $productCategoryTransfers
    ): void {
        $localeNameMapByStoreName = $this->getLocaleNameMapByStoreName();

        foreach ($localeNameMapByStoreName as $storeName => $storeLocales) {
            foreach ($storeLocales as $localeName) {
                $productAbstractCategoryStorageTransfers = $this->saveProductAbstractCategoryStorages(
                    $productCategoryTransfers,
                    $productAbstractCategoryStorageTransfers,
                    $productAbstractLocalizedAttributesTransfers,
                    $storeName,
                    $localeName,
                );
            }
        }

        $this->removeProductAbstractCategoryStorages($productAbstractCategoryStorageTransfers);
    }

    /**
     * @param array<array<\Generated\Shared\Transfer\ProductCategoryTransfer>> $productCategoryTransfers
     * @param array<array<array<\Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer>>> $productAbstractCategoryStorageTransfers
     * @param array<\Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer> $productAbstractLocalizedAttributesTransfers
     * @param string $storeName
     * @param string $localeName
     *
     * @return array<array<array<\Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer>>>
     */
    protected function saveProductAbstractCategoryStorages(
        array $productCategoryTransfers,
        array $productAbstractCategoryStorageTransfers,
        array $productAbstractLocalizedAttributesTransfers,
        string $storeName,
        string $localeName
    ): array {
        foreach ($productAbstractLocalizedAttributesTransfers as $productAbstractLocalizedAttributesTransfer) {
            $productAbstractCategoryStorageTransfers = $this->saveProductAbstractCategoryStorage(
                $productCategoryTransfers,
                $productAbstractCategoryStorageTransfers,
                $productAbstractLocalizedAttributesTransfer,
                $storeName,
                $localeName,
            );
        }

        return $productAbstractCategoryStorageTransfers;
    }

    /**
     * @param array<array<\Generated\Shared\Transfer\ProductCategoryTransfer>> $productCategoryTransfers
     * @param array<array<array<\Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer>>> $productAbstractCategoryStorageTransfers
     * @param \Generated\Shared\Transfer\ProductAbstractLocalizedAttributesTransfer $productAbstractLocalizedAttributesTransfer
     * @param string $storeName
     * @param string $localeName
     *
     * @return array<array<array<\Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer>>>
     */
    protected function saveProductAbstractCategoryStorage(
        array $productCategoryTransfers,
        array $productAbstractCategoryStorageTransfers,
        ProductAbstractLocalizedAttributesTransfer $productAbstractLocalizedAttributesTransfer,
        string $storeName,
        string $localeName
    ): array {
        $idProductAbstract = $productAbstractLocalizedAttributesTransfer->getIdProductAbstractOrFail();
        $productCategoryStorageTransfers = $this->productCategoryStorageReader->getProductCategoryStoragesFromCategoryTree(
            $productCategoryTransfers[$idProductAbstract] ?? [],
            $storeName,
            $localeName,
        );

        $productAbstractCategoryStorageTransfer
            = $productAbstractCategoryStorageTransfers[$idProductAbstract][$storeName][$localeName]
            ?? null;

        if ($productAbstractCategoryStorageTransfer) {
            unset($productAbstractCategoryStorageTransfers[$idProductAbstract][$storeName][$localeName]);
        }

        if (!count($productCategoryStorageTransfers) && $productAbstractCategoryStorageTransfer) {
            $this->productCategoryStorageEntityManager->deleteProductAbstractCategoryStorage(
                $idProductAbstract,
                $storeName,
                $localeName,
            );
        }

        if (!count($productCategoryStorageTransfers)) {
            return $productAbstractCategoryStorageTransfers;
        }

        $productAbstractCategoryStorageTransfer = (new ProductAbstractCategoryStorageTransfer())
            ->setCategories((new ArrayObject($productCategoryStorageTransfers)))
            ->setIdProductAbstract($idProductAbstract);

        $this->productCategoryStorageEntityManager->saveProductAbstractCategoryStorage(
            $idProductAbstract,
            $storeName,
            $localeName,
            $productAbstractCategoryStorageTransfer,
        );

        return $productAbstractCategoryStorageTransfers;
    }

    /**
     * @param array<(array<array<\Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer>>|array<array<array>>)> $productAbstractCategoryStorageTransfers
     *
     * @return void
     */
    protected function removeProductAbstractCategoryStorages(array $productAbstractCategoryStorageTransfers): void
    {
        foreach ($productAbstractCategoryStorageTransfers as $idProductAbstract => $relatedToProductTransfers) {
            foreach ($relatedToProductTransfers as $storeName => $relatedToStoreTransfers) {
                $this->removeLocalizedProductAbstractCategoryStorages($relatedToStoreTransfers, $idProductAbstract, $storeName);
            }
        }
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer|array> $relatedToStoreProductAbstractCategoryStorageTransfers
     * @param int $idProductAbstract
     * @param string $storeName
     *
     * @return void
     */
    protected function removeLocalizedProductAbstractCategoryStorages(
        array $relatedToStoreProductAbstractCategoryStorageTransfers,
        int $idProductAbstract,
        string $storeName
    ): void {
        foreach ($relatedToStoreProductAbstractCategoryStorageTransfers as $localeName => $productAbstractCategoryStorageTransfer) {
            if (!$productAbstractCategoryStorageTransfer) {
                continue;
            }

            $this->productCategoryStorageEntityManager->deleteProductAbstractCategoryStorage(
                $idProductAbstract,
                $storeName,
                $localeName,
            );
        }
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<array<\Generated\Shared\Transfer\ProductCategoryTransfer>>
     */
    protected function findProductCategories(array $productAbstractIds): array
    {
        $mappedProductCategoryTransfers = [];
        $productCategoryTransfers = $this->productCategoryStorageRepository
            ->getProductCategoryWithCategoryNodes($productAbstractIds, $this->storeFacade->getCurrentStore()->getNameOrFail());

        foreach ($productCategoryTransfers as $productCategoryTransfer) {
            $mappedProductCategoryTransfers[$productCategoryTransfer->getFkProductAbstract()][] = $productCategoryTransfer;
        }

        return $mappedProductCategoryTransfers;
    }

    /**
     * @return array<array<string>>
     */
    protected function getLocaleNameMapByStoreName(): array
    {
        $storeTransfers = $this->storeFacade->getAllStores();
        $localeNameMapByStoreName = [];

        foreach ($storeTransfers as $storeTransfer) {
            $localeNameMapByStoreName[$storeTransfer->getName()] = $storeTransfer->getAvailableLocaleIsoCodes();
        }

        return $localeNameMapByStoreName;
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    protected function writeCollectionByBatch(array $productAbstractIds): void
    {
        $productCategoryTransfers = $this->findProductCategories($productAbstractIds);
        $productAbstractLocalizedAttributesTransfers = $this->productCategoryStorageRepository
            ->getProductAbstractLocalizedAttributes($productAbstractIds);

        $productAbstractCategoryStorageTransfers = $this->productCategoryStorageRepository
            ->getMappedProductAbstractCategoryStorages($productAbstractIds);

        $this->storeData($productAbstractLocalizedAttributesTransfers, $productAbstractCategoryStorageTransfers, $productCategoryTransfers);
    }
}
