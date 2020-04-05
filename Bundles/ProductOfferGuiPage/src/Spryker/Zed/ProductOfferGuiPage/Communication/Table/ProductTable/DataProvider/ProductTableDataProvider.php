<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\DataProvider;

use Generated\Shared\Transfer\GuiTableDataTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductTableRowDataTransfer;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\ProductTable;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToTranslatorFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface;

class ProductTableDataProvider implements ProductTableDataProviderInterface
{
    protected const ATTRIBUTE_KEY_COLOR = 'color';

    protected const COLUMN_DATA_STATUS_ACTIVE = 'Active';
    protected const COLUMN_DATA_STATUS_INACTIVE = 'Inactive';

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface
     */
    protected $productOfferGuiPageRepository;

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @param \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface $productOfferGuiPageRepository
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        ProductOfferGuiPageRepositoryInterface $productOfferGuiPageRepository,
        ProductOfferGuiPageToTranslatorFacadeInterface $translatorFacade
    ) {
        $this->productOfferGuiPageRepository = $productOfferGuiPageRepository;
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer
     */
    public function getProductTableData(ProductTableCriteriaTransfer $productTableCriteriaTransfer): GuiTableDataTransfer
    {
        $productTableDataTransfer = $this->productOfferGuiPageRepository->getProductTableData($productTableCriteriaTransfer);
        $productTableDataArray = [];

        foreach ($productTableDataTransfer->getRows() as $productTableRowDataTransfer) {
            $productTableDataArray[] = [
                ProductTable::COL_KEY_SKU => $productTableRowDataTransfer->getSku(),
                ProductTable::COL_KEY_NAME => $this->getNameColumnData($productTableRowDataTransfer),
                ProductTable::COL_KEY_STORES => $this->getStoresColumnData($productTableRowDataTransfer),
                ProductTable::COL_KEY_IMAGE => $productTableRowDataTransfer->getImage(),
                ProductTable::COL_KEY_STATUS => $this->getStatusColumnData($productTableRowDataTransfer),
                ProductTable::COL_KEY_VALID_FROM => $productTableRowDataTransfer->getValidFrom(),
                ProductTable::COL_KEY_VALID_TO => $productTableRowDataTransfer->getValidTo(),
                ProductTable::COL_KEY_OFFERS => $productTableRowDataTransfer->getOffersCount(),
            ];
        }

        $paginationTransfer = $productTableDataTransfer->getPagination();

        return (new GuiTableDataTransfer())->setData($productTableDataArray)
            ->setPage($paginationTransfer->getPage())
            ->setPageSize($paginationTransfer->getMaxPerPage())
            ->setTotal($paginationTransfer->getNbResults());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableRowDataTransfer $productTableRowDataTransfer
     *
     * @return string|null
     */
    protected function getNameColumnData(ProductTableRowDataTransfer $productTableRowDataTransfer): ?string
    {
        $productConcreteName = $productTableRowDataTransfer->getName();

        if (!$productConcreteName) {
            return null;
        }

        $extendedProductConcreteNameParts = [$productConcreteName];
        $productConcreteAttributes = array_merge(
            $productTableRowDataTransfer->getProductConcreteAttributes(),
            $productTableRowDataTransfer->getProductConcreteLocalizedAttributes()
        );
        $productAbstractAttributes = array_merge(
            $productTableRowDataTransfer->getProductAbstractAttributes(),
            $productTableRowDataTransfer->getProductAbstractLocalizedAttributes()
        );

        foreach ($productConcreteAttributes as $productConcreteAttribute) {
            if (!$productConcreteAttribute) {
                continue;
            }

            $extendedProductConcreteNameParts[] = ucfirst($productConcreteAttribute);
        }

        if (!isset($productConcreteAttributes[static::ATTRIBUTE_KEY_COLOR]) && isset($productAbstractAttributes[static::ATTRIBUTE_KEY_COLOR])) {
            $extendedProductConcreteNameParts[] = ucfirst($productAbstractAttributes[static::ATTRIBUTE_KEY_COLOR]);
        }

        return implode(', ', $extendedProductConcreteNameParts);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableRowDataTransfer $productTableRowDataTransfer
     *
     * @return string[]|string
     */
    protected function getStoresColumnData(ProductTableRowDataTransfer $productTableRowDataTransfer)
    {
        $storesString = $productTableRowDataTransfer->getStores();
        $stores = explode(',', $storesString);

        if (count($stores) === 1) {
            return trim($stores[0]);
        }

        return $stores;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableRowDataTransfer $productTableRowDataTransfer
     *
     * @return string
     */
    protected function getStatusColumnData(ProductTableRowDataTransfer $productTableRowDataTransfer): string
    {
        $isActiveColumnData = $productTableRowDataTransfer->getIsActive()
            ? static::COLUMN_DATA_STATUS_ACTIVE
            : static::COLUMN_DATA_STATUS_INACTIVE;

        return $this->translatorFacade->trans($isActiveColumnData);
    }
}
