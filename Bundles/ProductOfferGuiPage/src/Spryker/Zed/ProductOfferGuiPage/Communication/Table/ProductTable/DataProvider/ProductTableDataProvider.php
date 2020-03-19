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
use Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface;

class ProductTableDataProvider implements ProductTableDataProviderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface
     */
    protected $productOfferGuiPageRepository;

    /**
     * @param \Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface $productOfferGuiPageRepository
     */
    public function __construct(ProductOfferGuiPageRepositoryInterface $productOfferGuiPageRepository)
    {
        $this->productOfferGuiPageRepository = $productOfferGuiPageRepository;
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
                ProductTable::COL_KEY_IMAGE => $this->getImageColumnData($productTableRowDataTransfer),
                ProductTable::COL_KEY_STATUS => $productTableRowDataTransfer->getIsActive(),
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

        foreach ($productTableRowDataTransfer->getAttributes() as $productConcreteAttribute) {
            if (!$productConcreteAttribute) {
                continue;
            }

            $extendedProductConcreteNameParts[] = ucfirst($productConcreteAttribute);
        }

        return implode(', ', $extendedProductConcreteNameParts);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableRowDataTransfer $productTableRowDataTransfer
     *
     * @return string|null
     */
    protected function getImageColumnData(ProductTableRowDataTransfer $productTableRowDataTransfer): ?string
    {
        $imagesString = $productTableRowDataTransfer->getImages();

        return explode(',', $imagesString)[0] ?? null;
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
            return $stores[0];
        }

        return $stores;
    }
}
