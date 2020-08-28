<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\ProductLabelCriteriaTransfer;
use Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer;
use Spryker\Zed\ProductLabelStorage\Business\Mapper\ProductLabelDictionaryItemMapper;
use Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToProductLabelFacadeInterface;
use Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToStoreFacadeInterface;
use Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManagerInterface;
use Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageRepositoryInterface;

class ProductLabelDictionaryStorageWriter implements ProductLabelDictionaryStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToProductLabelFacadeInterface
     */
    protected $productLabelFacade;

    /**
     * @var \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageRepositoryInterface
     */
    protected $productLabelStorageRepository;

    /**
     * @var \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManagerInterface
     */
    protected $productLabelStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductLabelStorage\Business\Mapper\ProductLabelDictionaryItemMapper
     */
    protected $productLabelDictionaryItemMapper;

    /**
     * @var \Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToProductLabelFacadeInterface $productLabelFacade
     * @param \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageRepositoryInterface $productLabelStorageRepository
     * @param \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManagerInterface $productLabelStorageEntityManager
     * @param \Spryker\Zed\ProductLabelStorage\Business\Mapper\ProductLabelDictionaryItemMapper $productLabelDictionaryItemMapper
     * @param \Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductLabelStorageToProductLabelFacadeInterface $productLabelFacade,
        ProductLabelStorageRepositoryInterface $productLabelStorageRepository,
        ProductLabelStorageEntityManagerInterface $productLabelStorageEntityManager,
        ProductLabelDictionaryItemMapper $productLabelDictionaryItemMapper,
        ProductLabelStorageToStoreFacadeInterface $storeFacade
    ) {
        $this->productLabelFacade = $productLabelFacade;
        $this->productLabelStorageRepository = $productLabelStorageRepository;
        $this->productLabelStorageEntityManager = $productLabelStorageEntityManager;
        $this->productLabelDictionaryItemMapper = $productLabelDictionaryItemMapper;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return void
     */
    public function writeProductLabelDictionaryStorageCollection(): void
    {
        $productLabelTransfers = $this->productLabelFacade
            ->getActiveLabelsByCriteria(new ProductLabelCriteriaTransfer());

        if (!$productLabelTransfers) {
            $this->productLabelStorageEntityManager->deleteAllProductLabelDictionaryStorageEntities();

            return;
        }

        $productLabelDictionaryItemTransfersMappedByStoreAndLocale = $this->productLabelDictionaryItemMapper
            ->mapProductLabelTransfersToProductLabelDictionaryItemTransfersByStoreNameAndLocaleName(
                $productLabelTransfers,
                $this->getLocaleNameMapByStoreName()
            );

        $productLabelDictionaryStorageTransfers = $this->productLabelStorageRepository
            ->getProductLabelDictionaryStorageTransfers();
        $productLabelDictionaryStorageTransfers = $this->filterAndDeleteEmptyProductLabelDictionaryStorageTransfers(
            $productLabelDictionaryStorageTransfers,
            $productLabelDictionaryItemTransfersMappedByStoreAndLocale
        );
        $productLabelDictionaryItemTransfersMappedByStoreAndLocale = $this->filterAndUpdateExistingProductLabelDictionaryStorageData(
            $productLabelDictionaryStorageTransfers,
            $productLabelDictionaryItemTransfersMappedByStoreAndLocale
        );

        $this->createProductLabelDictionaryStorageData($productLabelDictionaryItemTransfersMappedByStoreAndLocale);
    }

    /**
     * @return string[][]
     */
    protected function getLocaleNameMapByStoreName(): array
    {
        $localeNameMapByStoreName = [];
        $storeTransfers = $this->storeFacade->getAllStores();

        foreach ($storeTransfers as $storeTransfer) {
            $localeNameMapByStoreName[$storeTransfer->getName()] = $storeTransfer->getAvailableLocaleIsoCodes();
        }

        return $localeNameMapByStoreName;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer[] $productLabelDictionaryStorageTransfers
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[][] $productLabelDictionaryItemTransfersMappedByStoreAndLocale
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer[]
     */
    protected function filterAndDeleteEmptyProductLabelDictionaryStorageTransfers(
        array $productLabelDictionaryStorageTransfers,
        array $productLabelDictionaryItemTransfersMappedByStoreAndLocale
    ): array {
        foreach ($productLabelDictionaryStorageTransfers as $dataKey => $productLabelDictionaryStorageTransfer) {
            $storeName = $productLabelDictionaryStorageTransfer->getStore();
            $localeName = $productLabelDictionaryStorageTransfer->getLocale();

            if (isset($productLabelDictionaryItemTransfersMappedByStoreAndLocale[$storeName][$localeName])) {
                continue;
            }

            $this->productLabelStorageEntityManager->deleteProductLabelDictionaryStorageById(
                $productLabelDictionaryStorageTransfer->getIdProductLabelDictionaryStorage()
            );
            unset($productLabelDictionaryStorageTransfers[$dataKey]);
        }

        return $productLabelDictionaryStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer[] $productLabelDictionaryStorageTransfers
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[][] $productLabelDictionaryItemTransfersMappedByStoreAndLocale
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[][]
     */
    protected function filterAndUpdateExistingProductLabelDictionaryStorageData(
        array $productLabelDictionaryStorageTransfers,
        array $productLabelDictionaryItemTransfersMappedByStoreAndLocale
    ): array {
        foreach ($productLabelDictionaryStorageTransfers as $productLabelDictionaryStorageTransfer) {
            $storeName = $productLabelDictionaryStorageTransfer->getStore();
            $localeName = $productLabelDictionaryStorageTransfer->getLocale();

            $productLabelDictionaryStorageTransfer->setItems(
                new ArrayObject($productLabelDictionaryItemTransfersMappedByStoreAndLocale[$storeName][$localeName])
            );
            $this->productLabelStorageEntityManager->updateProductLabelDictionaryStorage(
                $productLabelDictionaryStorageTransfer
            );
            unset($productLabelDictionaryItemTransfersMappedByStoreAndLocale[$storeName][$localeName]);
        }

        return $productLabelDictionaryItemTransfersMappedByStoreAndLocale;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[][] $productLabelDictionaryItemTransfersMappedByStoreAndLocale
     *
     * @return void
     */
    protected function createProductLabelDictionaryStorageData(
        array $productLabelDictionaryItemTransfersMappedByStoreAndLocale
    ): void {
        foreach ($productLabelDictionaryItemTransfersMappedByStoreAndLocale as $storeName => $productLabelDictionaryItemTransfersMappedByLocale) {
            foreach ($productLabelDictionaryItemTransfersMappedByLocale as $localeName => $productLabelDictionaryItemTransfers) {
                $productLabelDictionaryStorageTransfer = (new ProductLabelDictionaryStorageTransfer())
                    ->setStore($storeName)
                    ->setLocale($localeName)
                    ->setItems(new ArrayObject($productLabelDictionaryItemTransfers));

                $this->productLabelStorageEntityManager->createProductLabelDictionaryStorage(
                    $productLabelDictionaryStorageTransfer
                );
            }
        }
    }
}
