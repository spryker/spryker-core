<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable;

use Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataTransfer;
use Generated\Shared\Transfer\GuiTableRowActionTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductTableDataTransfer;
use Generated\Shared\Transfer\ProductTableRowDataTransfer;
use Spryker\Zed\ProductOfferGuiPage\Business\ProductOfferGuiPageFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\CriteriaBuilder\ProductTableCriteriaBuilderInterface;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter\HasOffersProductTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter\IsActiveProductTableFilterDataProvider;
use Spryker\Zed\ProductOfferGuiPage\Exception\InvalidPaginationDataException;

class ProductTable extends AbstractTable
{
    protected const COL_KEY_NAME = 'name';
    protected const COL_KEY_SKU = 'sku';
    protected const COL_KEY_IMAGE = 'image';
    protected const COL_KEY_STORES = 'stores';
    protected const COL_KEY_STATUS = 'status';
    protected const COL_KEY_OFFERS = 'offers';
    protected const COL_KEY_VALID_FROM = 'validFrom';
    protected const COL_KEY_VALID_TO = 'validTo';

    protected const PATTERN_DATE_FORMAT = 'Y-m-d H:i:s';

    protected const SEARCH_PLACEHOLDER = 'Search by SKU, Name';

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Business\ProductOfferGuiPageFacadeInterface
     */
    protected $productOfferGuiPageFacade;

    /**
     * @var array|\Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter\ProductTableFilterDataProviderInterface[]
     */
    protected $productTableFilterDataProviders;

    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\CriteriaBuilder\ProductTableCriteriaBuilderInterface
     */
    protected $productTableCriteriaBuilder;

    /**
     * @param \Spryker\Zed\ProductOfferGuiPage\Business\ProductOfferGuiPageFacadeInterface $productOfferGuiPageFacade
     * @param \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter\ProductTableFilterDataProviderInterface[] $productTableFilterDataProviders
     * @param \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\CriteriaBuilder\ProductTableCriteriaBuilderInterface $productTableCriteriaBuilder
     */
    public function __construct(
        ProductOfferGuiPageFacadeInterface $productOfferGuiPageFacade,
        array $productTableFilterDataProviders,
        ProductTableCriteriaBuilderInterface $productTableCriteriaBuilder
    ) {
        $this->productOfferGuiPageFacade = $productOfferGuiPageFacade;
        $this->productTableFilterDataProviders = $productTableFilterDataProviders;
        $this->productTableCriteriaBuilder = $productTableCriteriaBuilder;
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer
     */
    protected function provideTableData(): GuiTableDataTransfer
    {
        $productTableCriteriaTransfer = $this->buildProductTableCriteriaTransfer();
        $productTableDataTransfer = $this->productOfferGuiPageFacade->getProductTableData($productTableCriteriaTransfer);

        return $this->mapProductTableDataTransferToTableDataTransfer($productTableDataTransfer, new GuiTableDataTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function buildTableConfiguration(): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer = new GuiTableConfigurationTransfer();
        $guiTableConfigurationTransfer = $this->addColumnsToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->addFiltersToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->addRowActionsToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->addSearchOptionsToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer->setDefaultSortColumn($this->getDefaultSortColumnKey());
        $guiTableConfigurationTransfer->setAllowedFilters($this->getAllowedFilterNames());

        return $guiTableConfigurationTransfer;
    }

    /**
     * @return string[]
     */
    protected function getAllowedFilterNames(): array
    {
        return [
            HasOffersProductTableFilterDataProvider::FILTER_NAME,
            IsActiveProductTableFilterDataProvider::FILTER_NAME,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addColumnsToConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_SKU)
                ->setTitle('Sku')
                ->setType('text')
                ->setSortable(true)
                ->setHideable(false)
                ->setMultiple(false)
        );
        $guiTableConfigurationTransfer->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_IMAGE)
                ->setTitle('Image')
                ->setType('image')
                ->setSortable(false)
                ->setHideable(false)
                ->setMultiple(false)
        );
        $guiTableConfigurationTransfer->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_NAME)
                ->setTitle('Name')
                ->setType('text')
                ->setSortable(true)
                ->setHideable(false)
                ->setMultiple(false)
        );
        $guiTableConfigurationTransfer->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_STORES)
                ->setTitle('Stores')
                ->setType('text')
                ->setSortable(false)
                ->setHideable(false)
                ->setMultiple(true)
        );
        $guiTableConfigurationTransfer->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_STATUS)
                ->setTitle('Status')
                ->setType('text')
                ->setSortable(true)
                ->setHideable(false)
                ->setMultiple(false)
        );
        $guiTableConfigurationTransfer->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_VALID_FROM)
                ->setTitle('Valid From')
                ->setType('date')
                ->addTypeOption('format', static::PATTERN_DATE_FORMAT)
                ->setSortable(false)
                ->setHideable(false)
                ->setMultiple(false)
        );
        $guiTableConfigurationTransfer->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_VALID_TO)
                ->setTitle('Valid To')
                ->setType('date')
                ->addTypeOption('format', static::PATTERN_DATE_FORMAT)
                ->setSortable(false)
                ->setHideable(false)
                ->setMultiple(false)
        );
        $guiTableConfigurationTransfer->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_OFFERS)
                ->setTitle('Offers')
                ->setType('text')
                ->setSortable(true)
                ->setMultiple(false)
        );

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addFiltersToConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        foreach ($this->productTableFilterDataProviders as $productTableFilterDataProvider) {
            $guiTableConfigurationTransfer->addFilter($productTableFilterDataProvider->getFilterData());
        }

        return $guiTableConfigurationTransfer;
    }

    /**
     * @return string
     */
    protected function getDefaultSortColumnKey(): string
    {
        return static::COL_KEY_SKU;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductTableCriteriaTransfer
     */
    protected function buildProductTableCriteriaTransfer(): ProductTableCriteriaTransfer
    {
        return $this->productTableCriteriaBuilder
            ->setSearchTerm($this->searchTerm)
            ->setPage($this->page)
            ->setPageSize($this->pageSize)
            ->setSorting($this->sorting)
            ->setFilters($this->filters)
            ->build();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableDataTransfer $productTableDataTransfer
     * @param \Generated\Shared\Transfer\GuiTableDataTransfer $guiTableDataTransfer
     *
     * @throws \Spryker\Zed\ProductOfferGuiPage\Exception\InvalidPaginationDataException
     *
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer
     */
    protected function mapProductTableDataTransferToTableDataTransfer(
        ProductTableDataTransfer $productTableDataTransfer,
        GuiTableDataTransfer $guiTableDataTransfer
    ): GuiTableDataTransfer {
        $tableRowsData = [];

        foreach ($productTableDataTransfer->getRows() as $productTableRowDataTransfer) {
            $tableRowsData[] = [
                static::COL_KEY_NAME => $this->buildNameColumnData($productTableRowDataTransfer),
                static::COL_KEY_SKU => $productTableRowDataTransfer->getSku(),
                static::COL_KEY_IMAGE => $productTableRowDataTransfer->getImage(),
                static::COL_KEY_STORES => $this->buildStoresColumnData($productTableRowDataTransfer),
                static::COL_KEY_OFFERS => $productTableRowDataTransfer->getOffersCount() ?? 0,
                static::COL_KEY_STATUS => $productTableRowDataTransfer->getIsActive(),
                static::COL_KEY_VALID_FROM => $productTableRowDataTransfer->getValidFrom(),
                static::COL_KEY_VALID_TO => $productTableRowDataTransfer->getValidTo(),
            ];
        }

        $paginationTransfer = $productTableDataTransfer->getPagination();

        if (!$paginationTransfer) {
            throw new InvalidPaginationDataException('Pagination data is not present.');
        }

        return $guiTableDataTransfer->setData($tableRowsData)
            ->setPage($paginationTransfer->getPage())
            ->setSize($paginationTransfer->getMaxPerPage())
            ->setTotal($paginationTransfer->getNbResults());
    }

    /**
     * TODO: url needs to be adjusted once the create offer part is ready.
     *
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addRowActionsToConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableRowActionTransfer = (new GuiTableRowActionTransfer())
            ->setId('create-offer')
            ->setTitle('Create Offer')
            ->setType('form-overlay')
            ->setUrl('https://path-to-create-offer-action/${row.sku}')
            ->setIcon('icon-name');

        $guiTableConfigurationTransfer->addRowAction($guiTableRowActionTransfer);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addSearchOptionsToConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer->addSearchOption('placeholder', static::SEARCH_PLACEHOLDER);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableRowDataTransfer $productTableRowDataTransfer
     *
     * @return string[]|string
     */
    protected function buildStoresColumnData(ProductTableRowDataTransfer $productTableRowDataTransfer)
    {
        $productConcreteStores = explode(',', $productTableRowDataTransfer->getStores());

        if (count($productConcreteStores) === 1) {
            return $productConcreteStores[0];
        }

        return $productConcreteStores;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableRowDataTransfer $productTableRowDataTransfer
     *
     * @return string|null
     */
    protected function buildNameColumnData(ProductTableRowDataTransfer $productTableRowDataTransfer): ?string
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
}
