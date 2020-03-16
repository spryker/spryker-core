<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductTableDataTransfer;
use Generated\Shared\Transfer\TableColumnConfigurationTransfer;
use Generated\Shared\Transfer\TableConfigurationTransfer;
use Generated\Shared\Transfer\TableDataTransfer;
use Generated\Shared\Transfer\TableRowActionTransfer;
use Spryker\Zed\ProductOfferGuiPage\Business\ProductOfferGuiPageFacadeInterface;
use Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\CriteriaBuilder\ProductTableCriteriaBuilderInterface;
use Spryker\Zed\ProductOfferGuiPage\Exception\InvalidPaginationDataException;
use Spryker\Zed\ProductOfferGuiPage\ProductOfferGuiPageConfig;

class ProductTable extends AbstractTable
{
    protected const COLUMN_KEY_NAME = 'name';
    protected const COLUMN_KEY_SKU = 'sku';
    protected const COLUMN_KEY_IMAGE = 'image';
    protected const COLUMN_KEY_STORES = 'stores';
    protected const COLUMN_KEY_STATUS = 'status';
    protected const COLUMN_KEY_OFFERS = 'offers';
    protected const COLUMN_KEY_VALID_FROM = 'validFrom';
    protected const COLUMN_KEY_VALID_TO = 'validTo';

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
     * @var \Spryker\Zed\ProductOfferGuiPage\ProductOfferGuiPageConfig
     */
    protected $productOfferGuiPageConfig;

    /**
     * @param \Spryker\Zed\ProductOfferGuiPage\Business\ProductOfferGuiPageFacadeInterface $productOfferGuiPageFacade
     * @param \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\Filter\ProductTableFilterDataProviderInterface[] $productTableFilterDataProviders
     * @param \Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductTable\CriteriaBuilder\ProductTableCriteriaBuilderInterface $productTableCriteriaBuilder
     * @param \Spryker\Zed\ProductOfferGuiPage\ProductOfferGuiPageConfig $productOfferGuiPageConfig
     */
    public function __construct(
        ProductOfferGuiPageFacadeInterface $productOfferGuiPageFacade,
        array $productTableFilterDataProviders,
        ProductTableCriteriaBuilderInterface $productTableCriteriaBuilder,
        ProductOfferGuiPageConfig $productOfferGuiPageConfig
    ) {
        $this->productOfferGuiPageFacade = $productOfferGuiPageFacade;
        $this->productTableFilterDataProviders = $productTableFilterDataProviders;
        $this->productTableCriteriaBuilder = $productTableCriteriaBuilder;
        $this->productOfferGuiPageConfig = $productOfferGuiPageConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\TableDataTransfer
     */
    protected function provideTableData(): TableDataTransfer
    {
        $productTableCriteriaTransfer = $this->buildProductTableCriteriaTransfer();
        $productTableDataTransfer = $this->productOfferGuiPageFacade->getProductTableData($productTableCriteriaTransfer);

        return $this->mapProductTableDataTransferToTableDataTransfer($productTableDataTransfer, new TableDataTransfer());
    }

    /**
     * @return \Generated\Shared\Transfer\TableConfigurationTransfer
     */
    protected function buildTableConfiguration(): TableConfigurationTransfer
    {
        $tableConfigurationTransfer = new TableConfigurationTransfer();
        $tableConfigurationTransfer = $this->addColumnsToConfiguration($tableConfigurationTransfer);
        $tableConfigurationTransfer = $this->addFiltersToConfiguration($tableConfigurationTransfer);
        $tableConfigurationTransfer = $this->addRowActionsToConfiguration($tableConfigurationTransfer);
        $tableConfigurationTransfer = $this->addSearchOptionsToConfiguration($tableConfigurationTransfer);
        $tableConfigurationTransfer->setDefaultSortColumn($this->getDefaultSortColumnKey());
        $tableConfigurationTransfer->setAllowedFilters($this->productOfferGuiPageConfig->getAllowedFilterNames());

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
            (new TableColumnConfigurationTransfer())
                ->setId(static::COLUMN_KEY_SKU)
                ->setTitle('Sku')
                ->setType('text')
                ->setSortable(true)
                ->setHideable(false)
                ->setMultiple(false)
        );
        $tableConfigurationTransfer->addColumn(
            (new TableColumnConfigurationTransfer())
                ->setId(static::COLUMN_KEY_IMAGE)
                ->setTitle('Image')
                ->setType('image')
                ->setSortable(false)
                ->setHideable(false)
                ->setMultiple(false)
        );
        $tableConfigurationTransfer->addColumn(
            (new TableColumnConfigurationTransfer())
                ->setId(static::COLUMN_KEY_NAME)
                ->setTitle('Name')
                ->setType('text')
                ->setSortable(true)
                ->setHideable(false)
                ->setMultiple(false)
        );
        $tableConfigurationTransfer->addColumn(
            (new TableColumnConfigurationTransfer())
                ->setId(static::COLUMN_KEY_STORES)
                ->setTitle('Stores')
                ->setType('text')
                ->setSortable(false)
                ->setHideable(false)
                ->setMultiple(true)
        );
        $tableConfigurationTransfer->addColumn(
            (new TableColumnConfigurationTransfer())
                ->setId(static::COLUMN_KEY_STATUS)
                ->setTitle('Status')
                ->setType('text')
                ->setSortable(true)
                ->setHideable(false)
                ->setMultiple(false)
        );
        $tableConfigurationTransfer->addColumn(
            (new TableColumnConfigurationTransfer())
                ->setId(static::COLUMN_KEY_VALID_FROM)
                ->setTitle('Valid From')
                ->setType('date')
                ->addTypeOption('format', static::PATTERN_DATE_FORMAT)
                ->setSortable(false)
                ->setHideable(false)
                ->setMultiple(false)
        );
        $tableConfigurationTransfer->addColumn(
            (new TableColumnConfigurationTransfer())
                ->setId(static::COLUMN_KEY_VALID_TO)
                ->setTitle('Valid To')
                ->setType('date')
                ->addTypeOption('format', static::PATTERN_DATE_FORMAT)
                ->setSortable(false)
                ->setHideable(false)
                ->setMultiple(false)
        );
        $tableConfigurationTransfer->addColumn(
            (new TableColumnConfigurationTransfer())
                ->setId(static::COLUMN_KEY_OFFERS)
                ->setTitle('Offers')
                ->setType('text')
                ->setSortable(true)
                ->setMultiple(false)
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
        foreach ($this->productTableFilterDataProviders as $productTableFilterDataProvider) {
            $tableConfigurationTransfer->addFilter($productTableFilterDataProvider->getFilterData());
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
     * @param \Generated\Shared\Transfer\TableDataTransfer $tableDataTransfer
     *
     * @throws \Spryker\Zed\ProductOfferGuiPage\Exception\InvalidPaginationDataException
     *
     * @return \Generated\Shared\Transfer\TableDataTransfer
     */
    protected function mapProductTableDataTransferToTableDataTransfer(
        ProductTableDataTransfer $productTableDataTransfer,
        TableDataTransfer $tableDataTransfer
    ): TableDataTransfer {
        $tableRowsData = [];

        foreach ($productTableDataTransfer->getConcreteProducts() as $productConcreteTransfer) {
            $tableRowsData[] = [
                static::COLUMN_KEY_NAME => $productConcreteTransfer->getName(),
                static::COLUMN_KEY_SKU => $productConcreteTransfer->getSku(),
                static::COLUMN_KEY_IMAGE => $this->getImage($productConcreteTransfer),
                static::COLUMN_KEY_STORES => $productConcreteTransfer->getStoreNames()
                    ? explode(',', $productConcreteTransfer->getStoreNames())
                    : [],
                static::COLUMN_KEY_OFFERS => $productConcreteTransfer->getOffersCount() ?? 0,
                static::COLUMN_KEY_VALID_FROM => $productConcreteTransfer->getValidFrom(),
                static::COLUMN_KEY_VALID_TO => $productConcreteTransfer->getValidTo(),
            ];
        }

        $paginationTransfer = $productTableDataTransfer->getPagination();

        if (!$paginationTransfer) {
            throw new InvalidPaginationDataException('Pagination data is not present.');
        }

        return $tableDataTransfer->setData($tableRowsData)
            ->setPage($paginationTransfer->getPage())
            ->setSize($paginationTransfer->getMaxPerPage())
            ->setTotal($paginationTransfer->getNbResults());
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

    /**
     * TODO: url needs to be adjusted once the create offer part is ready.
     *
     * @param \Generated\Shared\Transfer\TableConfigurationTransfer $tableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\TableConfigurationTransfer
     */
    protected function addRowActionsToConfiguration(TableConfigurationTransfer $tableConfigurationTransfer): TableConfigurationTransfer
    {
        $tableRowActionTransfer = (new TableRowActionTransfer())
            ->setId('create-offer')
            ->setTitle('Create Offer')
            ->setType('form-overlay')
            ->setUrl('https://path-to-create-offer-action/${row.sku}')
            ->setIcon('icon-name');

        $tableConfigurationTransfer->addRowAction($tableRowActionTransfer);

        return $tableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TableConfigurationTransfer $tableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\TableConfigurationTransfer
     */
    protected function addSearchOptionsToConfiguration(TableConfigurationTransfer $tableConfigurationTransfer): TableConfigurationTransfer
    {
        $tableConfigurationTransfer->addSearchOption('placeholder', static::SEARCH_PLACEHOLDER);

        return $tableConfigurationTransfer;
    }
}
