<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductListTableCriteriaTransfer;
use Generated\Shared\Transfer\TableColumnTransfer;
use Generated\Shared\Transfer\TableConfigurationTransfer;
use Generated\Shared\Transfer\TableDataTransfer;
use Spryker\Zed\ProductOfferGuiPage\Business\ProductOfferGuiPageFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter\CategoryProductListTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter\IsActiveProductListTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter\StoresProductListTableFilterDataProvider;

class ProductListTable extends AbstractTable
{
    protected const COLUMN_KEY_NAME = 'name';
    protected const COLUMN_KEY_SKU = 'sku';
    protected const COLUMN_KEY_IMAGE = 'image';
    protected const COLUMN_KEY_STORES = 'stores';
    protected const COLUMN_KEY_STATUS = 'status';
    protected const COLUMN_KEY_HAS_OFFERS = 'hasOffers';
    protected const COLUMN_KEY_VALID_FROM = 'validFrom';
    protected const COLUMN_KEY_VALID_TO = 'validTo';

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Business\ProductOfferGuiPageFacadeInterface
     */
    protected $ProductOfferGuiPageFacade;

    /**
     * @var array|\Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter\ProductListTableFilterDataProviderInterface[]
     */
    protected $productListTableFilterDataProviders;

    /**
     * @param \Spryker\Zed\ProductOfferGuiPage\Business\ProductOfferGuiPageFacadeInterface $ProductOfferGuiPageFacade
     * @param \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter\ProductListTableFilterDataProviderInterface[] $productListTableFilterDataProviders
     */
    public function __construct(ProductOfferGuiPageFacadeInterface $ProductOfferGuiPageFacade, array $productListTableFilterDataProviders)
    {
        $this->ProductOfferGuiPageFacade = $ProductOfferGuiPageFacade;
        $this->productListTableFilterDataProviders = $productListTableFilterDataProviders;
    }

    /**
     * @return \Generated\Shared\Transfer\TableDataTransfer
     */
    protected function provideTableData(): TableDataTransfer
    {
        $productListTableCriteriaTransfer = $this->buildProductLIstTableCriteriaTransfer();
        $productConcreteCollectionTransfer = $this->ProductOfferGuiPageFacade->getProductListTableData($productListTableCriteriaTransfer);

        return $this->mapProductConcreteCollectionTransferToTableDataTransfer($productConcreteCollectionTransfer, new TableDataTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\TableConfigurationTransfer
     */
    protected function provideTableConfiguration(): TableConfigurationTransfer
    {
        $tableConfigurationTransfer = new TableConfigurationTransfer();
        $tableConfigurationTransfer = $this->addColumnsToConfiguration($tableConfigurationTransfer);
        $tableConfigurationTransfer = $this->addFiltersToConfiguration($tableConfigurationTransfer);
        $tableConfigurationTransfer->setDefaultSortColumn($this->getDefaultSortColumnKey());

        return $tableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TableConfigurationTransfer $tableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\TableConfigurationTransfer
     */
    protected function addColumnsToConfiguration(TableConfigurationTransfer $tableConfigurationTransfer): TableConfigurationTransfer
    {
        $tableConfigurationTransfer->addColumn(
            (new TableColumnTransfer())
                ->setColumnKey(static::COLUMN_KEY_SKU)
                ->setColumnTitle('Sku')
                ->setIsSearchable(true)
                ->setIsSortable(true)
        );
        $tableConfigurationTransfer->addColumn(
            (new TableColumnTransfer())
                ->setColumnKey(static::COLUMN_KEY_IMAGE)
                ->setColumnTitle('Image')
                ->setIsSearchable(false)
                ->setIsSortable(false)
        );
        $tableConfigurationTransfer->addColumn(
            (new TableColumnTransfer())
                ->setColumnKey(static::COLUMN_KEY_NAME)
                ->setColumnTitle('Name')
                ->setIsSearchable(true)
                ->setIsSortable(true)
        );
        $tableConfigurationTransfer->addColumn(
            (new TableColumnTransfer())
                ->setColumnKey(static::COLUMN_KEY_STORES)
                ->setColumnTitle('Stores')
                ->setIsSearchable(false)
                ->setIsSortable(false)
        );
        $tableConfigurationTransfer->addColumn(
            (new TableColumnTransfer())
                ->setColumnKey(static::COLUMN_KEY_STATUS)
                ->setColumnTitle('Status')
                ->setIsSearchable(false)
                ->setIsSortable(true)
        );
        $tableConfigurationTransfer->addColumn(
            (new TableColumnTransfer())
                ->setColumnKey(static::COLUMN_KEY_HAS_OFFERS)
                ->setColumnTitle('Offers')
                ->setIsSearchable(false)
                ->setIsSortable(true)
        );
        $tableConfigurationTransfer->addColumn(
            (new TableColumnTransfer())
                ->setColumnKey(static::COLUMN_KEY_VALID_FROM)
                ->setColumnTitle('Valid From')
                ->setIsSearchable(false)
                ->setIsSortable(false)
        );
        $tableConfigurationTransfer->addColumn(
            (new TableColumnTransfer())
                ->setColumnKey(static::COLUMN_KEY_VALID_TO)
                ->setColumnTitle('Valid To')
                ->setIsSearchable(false)
                ->setIsSortable(false)
        );

        return $tableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TableConfigurationTransfer $tableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\TableConfigurationTransfer
     */
    protected function addFiltersToConfiguration(TableConfigurationTransfer $tableConfigurationTransfer): TableConfigurationTransfer
    {
        foreach ($this->productListTableFilterDataProviders as $productListTableFilterDataProvider) {
            $tableConfigurationTransfer->addFilter($productListTableFilterDataProvider->getFilterData());
        }

        return $tableConfigurationTransfer;
    }

    /**
     * @return string
     */
    protected function getDefaultSortColumnKey(): string
    {
        return static::COLUMN_KEY_NAME;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductListTableCriteriaTransfer
     */
    protected function buildProductLIstTableCriteriaTransfer(): ProductListTableCriteriaTransfer
    {
        $productListTableCriteriaTransfer = new ProductListTableCriteriaTransfer();
        $paginationTransfer = (new PaginationTransfer())
            ->setPage($this->page)
            ->setMaxPerPage($this->pageSize);
        $productListTableCriteriaTransfer->setSearchTerm($this->searchTerm);
        $productListTableCriteriaTransfer->setOrderBy($this->sorting);
        $productListTableCriteriaTransfer->setPagination($paginationTransfer);
        $this->addFilterDataToProductListTableCriteriaTransfer($productListTableCriteriaTransfer);

        return $productListTableCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListTableCriteriaTransfer $productListTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTableCriteriaTransfer
     */
    protected function addFilterDataToProductListTableCriteriaTransfer(ProductListTableCriteriaTransfer $productListTableCriteriaTransfer): ProductListTableCriteriaTransfer
    {
        if (!$this->filters) {
            return $productListTableCriteriaTransfer;
        }

        if (array_key_exists(CategoryProductListTableFilterDataProvider::FILTER_NAME, $this->filters)) {
            $productListTableCriteriaTransfer->setInCategories(
                $this->filters[CategoryProductListTableFilterDataProvider::FILTER_NAME]
            );
        }

        if (array_key_exists(IsActiveProductListTableFilterDataProvider::FILTER_NAME, $this->filters)) {
            $productListTableCriteriaTransfer->setIsActive(true);
        }

        if (array_key_exists(StoresProductListTableFilterDataProvider::FILTER_NAME, $this->filters)) {
            $productListTableCriteriaTransfer->setInStores(
                $this->filters[StoresProductListTableFilterDataProvider::FILTER_NAME]
            );
        }

        return $productListTableCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     * @param \Generated\Shared\Transfer\TableDataTransfer $tableDataTransfer
     *
     * @return \Generated\Shared\Transfer\TableDataTransfer
     */
    protected function mapProductConcreteCollectionTransferToTableDataTransfer(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer,
        TableDataTransfer $tableDataTransfer
    ): TableDataTransfer {
        $tableRows = [];

        foreach ($productConcreteCollectionTransfer->getConcreteProducts() as $productConcreteTransfer) {
            $tableRows[] = [
                static::COLUMN_KEY_NAME => $productConcreteTransfer->getName(),
                static::COLUMN_KEY_SKU => $productConcreteTransfer->getSku(),
                static::COLUMN_KEY_IMAGE => $this->getImage($productConcreteTransfer),
                static::COLUMN_KEY_STORES => $productConcreteTransfer->getStoreNames(),
                static::COLUMN_KEY_HAS_OFFERS => $productConcreteTransfer->getHasOffers() ?? false,
                static::COLUMN_KEY_VALID_FROM => $productConcreteTransfer->getValidFrom(),
                static::COLUMN_KEY_VALID_TO => $productConcreteTransfer->getValidTo(),
            ];
        }

        $tableDataTransfer->setRows($tableRows);
        $tableDataTransfer->setPagination($productConcreteCollectionTransfer->getPagination());

        return $tableDataTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string|null
     */
    protected function getImage(ProductConcreteTransfer $productConcreteTransfer): ?string
    {
        if (!$productConcreteTransfer->getImageSets()->count()) {
            return null;
        }

        return $productConcreteTransfer->getImageSets()[0]->getProductImages()[0]->getExternalUrlSmall();
    }
}
