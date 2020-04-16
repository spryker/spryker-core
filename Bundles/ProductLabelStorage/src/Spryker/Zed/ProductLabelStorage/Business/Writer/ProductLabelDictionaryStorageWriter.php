<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Writer;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;
use Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer;
use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToProductLabelFacadeInterface;
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
     * @param \Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToProductLabelFacadeInterface $productLabelFacade
     * @param \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageRepositoryInterface $productLabelStorageRepository
     * @param \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManagerInterface $productLabelStorageEntityManager
     */
    public function __construct(
        ProductLabelStorageToProductLabelFacadeInterface $productLabelFacade,
        ProductLabelStorageRepositoryInterface $productLabelStorageRepository,
        ProductLabelStorageEntityManagerInterface $productLabelStorageEntityManager
    ) {
        $this->productLabelFacade = $productLabelFacade;
        $this->productLabelStorageRepository = $productLabelStorageRepository;
        $this->productLabelStorageEntityManager = $productLabelStorageEntityManager;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductLabelStorage\Business\Writer\ProductLabelDictionaryStorageWriter::writeProductLabelDictionaryStorageCollection()} instead.
     *
     * @return void
     */
    public function publish()
    {
        $this->writeProductLabelDictionaryStorageCollection();
    }

    /**
     * @return void
     */
    public function writeProductLabelDictionaryStorageCollection(): void
    {
        $productLabelLocalizedAttributeTransfers = $this->productLabelFacade->getProductLabelLocalizedAttributes();

        $productLabelDictionaryItems = [];
        foreach ($productLabelLocalizedAttributeTransfers as $productLabelLocalizedAttributesTransfer) {
            $productLabelLocalizedAttributesTransfer
                ->requireLocale()
                ->requireProductLabel();

            $localeName = $productLabelLocalizedAttributesTransfer->getLocale()->getLocaleName();
            if ($this->isValidByDate($productLabelLocalizedAttributesTransfer->getProductLabel())) {
                $productLabelDictionaryItems[$localeName][] = $this->mapProductLabelDictionaryItem($productLabelLocalizedAttributesTransfer);
            }
        }

        if ($productLabelDictionaryItems === []) {
            $this->productLabelStorageEntityManager->deleteAllProductLabelDictionaryStorageEntities();

            return;
        }

        $this->storeData($productLabelDictionaryItems);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[][] $productLabelDictionaryItems
     *
     * @return void
     */
    protected function storeData(array $productLabelDictionaryItems)
    {
        $productLabelStorageTransfers = $this->productLabelStorageRepository->getProductLabelDictionaryStorageTransfers();
        $indexedProductLabelStorageTransfers = $this->indexProductLabelDictionaryTransfersByLocale($productLabelStorageTransfers);

        foreach ($productLabelDictionaryItems as $localeName => $productLabelDictionaryItemTransfers) {
            $productLabelDictionaryStorageTransfer = $indexedProductLabelStorageTransfers[$localeName] ?? new ProductLabelDictionaryStorageTransfer();
            $productLabelDictionaryStorageTransfer->setItems(new ArrayObject($productLabelDictionaryItemTransfers));

            $this->productLabelStorageEntityManager->saveProductLabelDictionaryStorage($productLabelDictionaryStorageTransfer, $localeName);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer $productLabelLocalizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer
     */
    protected function mapProductLabelDictionaryItem(
        ProductLabelLocalizedAttributesTransfer $productLabelLocalizedAttributesTransfer
    ): ProductLabelDictionaryItemTransfer {
        $productLabel = $productLabelLocalizedAttributesTransfer->getProductLabel();

        return (new ProductLabelDictionaryItemTransfer())
            ->setName($productLabelLocalizedAttributesTransfer->getName())
            ->setIdProductLabel($productLabelLocalizedAttributesTransfer->getFkProductLabel())
            ->setKey($productLabel->getName())
            ->setIsExclusive($productLabel->getIsExclusive())
            ->setPosition($productLabel->getPosition())
            ->setFrontEndReference($productLabel->getFrontEndReference());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer[] $productLabelDictionaryTransfers
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer[]
     */
    protected function indexProductLabelDictionaryTransfersByLocale(array $productLabelDictionaryTransfers): array
    {
        $indexedProductLabelDictionaryTransfers = [];
        foreach ($productLabelDictionaryTransfers as $productLabelDictionaryTransfer) {
            $indexedProductLabelDictionaryTransfers[$productLabelDictionaryTransfer->getLocale()] = $productLabelDictionaryTransfer;
        }

        return $indexedProductLabelDictionaryTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return bool
     */
    protected function isValidByDate(ProductLabelTransfer $productLabelTransfer): bool
    {
        return $this->isValidByDateFrom($productLabelTransfer) && $this->isValidByDateTo($productLabelTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return bool
     */
    protected function isValidByDateFrom(ProductLabelTransfer $productLabelTransfer): bool
    {
        if (!$productLabelTransfer->getValidFrom()) {
            return true;
        }

        $now = new DateTime();

        return $now > new DateTime($productLabelTransfer->getValidFrom());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return bool
     */
    protected function isValidByDateTo(ProductLabelTransfer $productLabelTransfer): bool
    {
        if (!$productLabelTransfer->getValidTo()) {
            return true;
        }

        $now = new DateTime();

        return $now < new DateTime($productLabelTransfer->getValidTo());
    }
}
