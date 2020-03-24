<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\DataProvider;

use Generated\Shared\Transfer\GuiTableDataTransfer;
use Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTableRowDataTransfer;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductOfferTable\ProductOfferTable;
use Spryker\Zed\ProductOfferGuiPage\Persistence\ProductOfferGuiPageRepositoryInterface;

class ProductOfferTableDataProvider implements ProductOfferTableDataProviderInterface
{
    protected const ATTRIBUTE_KEY_COLOR = 'color';

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
     * @param \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer
     */
    public function getProductOfferTableData(ProductOfferTableCriteriaTransfer $productOfferTableCriteriaTransfer): GuiTableDataTransfer
    {
        $productOfferTableDataTransfer = $this->productOfferGuiPageRepository->getProductOfferTableData($productOfferTableCriteriaTransfer);
        $productTableDataArray = [];

        foreach ($productOfferTableDataTransfer->getRows() as $productOfferTableRowDataTransfer) {
            $productTableDataArray[] = [
                ProductOfferTable::COL_KEY_MERCHANT_SKU => $productOfferTableRowDataTransfer->getMerchantSku(),
                ProductOfferTable::COL_KEY_OFFER_REFERENCE => $productOfferTableRowDataTransfer->getOfferReference(),
                ProductOfferTable::COL_KEY_STORES => $this->getStoresColumnData($productOfferTableRowDataTransfer),
                ProductOfferTable::COL_KEY_IMAGE => $productOfferTableRowDataTransfer->getImage(),
                ProductOfferTable::COL_KEY_APPROVAL_STATUS => $productOfferTableRowDataTransfer->getApprovalStatus(),
                ProductOfferTable::COL_KEY_PRODUCT_NAME => $this->getNameColumnData($productOfferTableRowDataTransfer),
                ProductOfferTable::COL_KEY_VALID_FROM => $productOfferTableRowDataTransfer->getValidFrom(),
                ProductOfferTable::COL_KEY_VALID_TO => $productOfferTableRowDataTransfer->getValidTo(),
                ProductOfferTable::COL_KEY_CREATED_AT => $productOfferTableRowDataTransfer->getCreatedAt(),
                ProductOfferTable::COL_KEY_UPDATED_AT => $productOfferTableRowDataTransfer->getUpdatedAt(),
            ];
        }

        $paginationTransfer = $productOfferTableDataTransfer->getPagination();

        return (new GuiTableDataTransfer())->setData($productTableDataArray)
            ->setPage($paginationTransfer->getPage())
            ->setPageSize($paginationTransfer->getMaxPerPage())
            ->setTotal($paginationTransfer->getNbResults());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTableRowDataTransfer $productOfferTableRowDataTransfer
     *
     * @return string|null
     */
    protected function getNameColumnData(ProductOfferTableRowDataTransfer $productOfferTableRowDataTransfer): ?string
    {
        $productConcreteName = $productOfferTableRowDataTransfer->getProductConcreteName();

        if (!$productConcreteName) {
            return null;
        }

        $extendedProductConcreteNameParts = [$productConcreteName];
        $productConcreteAttributes = $productOfferTableRowDataTransfer->getProductConcreteAttributes();
        $productAbstractAttributes = $productOfferTableRowDataTransfer->getProductAbstractAttributes();

        foreach ($productConcreteAttributes as $productConcreteAttribute) {
            if (!$productConcreteAttribute) {
                continue;
            }

            $extendedProductConcreteNameParts[] = ucfirst($productConcreteAttribute);
        }

        if (array_key_exists(static::ATTRIBUTE_KEY_COLOR, $productAbstractAttributes)) {
            $extendedProductConcreteNameParts[] = ucfirst($productAbstractAttributes[static::ATTRIBUTE_KEY_COLOR]);
        }

        return implode(', ', $extendedProductConcreteNameParts);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTableRowDataTransfer $productOfferTableRowDataTransfer
     *
     * @return string[]|string
     */
    protected function getStoresColumnData(ProductOfferTableRowDataTransfer $productOfferTableRowDataTransfer)
    {
        $storesString = $productOfferTableRowDataTransfer->getStores();
        $stores = explode(',', $storesString);

        if (count($stores) === 1) {
            return trim($stores[0]);
        }

        return $stores;
    }
}
