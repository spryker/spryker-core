<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable;

use Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataTransfer;
use Generated\Shared\Transfer\GuiTableRowActionTransfer;
use Generated\Shared\Transfer\ProductCriteriaFilterTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\AbstractTable;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\CriteriaBuilder\ProductCriteriaFilterBuilderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\DataProvider\ProductTableDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface;

class ProductTable extends AbstractTable
{
    public const COL_KEY_NAME = 'name';
    public const COL_KEY_SKU = 'sku';
    public const COL_KEY_IMAGE = 'image';
    public const COL_KEY_STORES = 'stores';
    public const COL_KEY_STATUS = 'status';
    public const COL_KEY_OFFERS = 'offers';
    public const COL_KEY_VALID_FROM = 'validFrom';
    public const COL_KEY_VALID_TO = 'validTo';

    protected const PATTERN_DATE_FORMAT = 'dd.MM.y';

    protected const SEARCH_PLACEHOLDER = 'Search by SKU, Name';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\CreateOfferController::tableDataAction()
     */
    protected const DATA_URL = '/product-offer-merchant-portal-gui/create-offer/table-data';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\DataProvider\ProductTableDataProviderInterface
     */
    protected $productTableDataProvider;

    /**
     * @var array|\Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterInterface[]
     */
    protected $productTableFilters;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\CriteriaBuilder\ProductCriteriaFilterBuilderInterface
     */
    protected $productCriteriaFilterBuilder;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\DataProvider\ProductTableDataProviderInterface $productTableDataProvider
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterInterface[] $productTableFilters
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\CriteriaBuilder\ProductCriteriaFilterBuilderInterface $productCriteriaFilterBuilder
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService,
        ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        ProductTableDataProviderInterface $productTableDataProvider,
        array $productTableFilters,
        ProductCriteriaFilterBuilderInterface $productCriteriaFilterBuilder
    ) {
        parent::__construct($utilEncodingService, $translatorFacade);
        $this->productTableDataProvider = $productTableDataProvider;
        $this->productTableFilters = $productTableFilters;
        $this->productCriteriaFilterBuilder = $productCriteriaFilterBuilder;
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer
     */
    protected function provideTableData(): GuiTableDataTransfer
    {
        $productCriteriaFilterTransfer = $this->buildProductCriteriaFilterTransfer();

        return $this->productTableDataProvider->getProductTableData($productCriteriaFilterTransfer);
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
        $guiTableConfigurationTransfer = $this->addSearchToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer->setDefaultSortColumn($this->getDefaultSortColumnKey());
        $guiTableConfigurationTransfer->setDataUrl(static::DATA_URL);

        return $guiTableConfigurationTransfer;
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
                ->setTitle('SKU')
                ->setType(static::COLUMN_TYPE_TEXT)
                ->setSortable(true)
                ->setHideable(false)
        )->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_IMAGE)
                ->setTitle('Image')
                ->setType(static::COLUMN_TYPE_IMAGE)
                ->setSortable(false)
                ->setHideable(false)
        )->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_NAME)
                ->setTitle('Name')
                ->setType(static::COLUMN_TYPE_TEXT)
                ->setSortable(true)
                ->setHideable(false)
        )->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_STORES)
                ->setTitle('Stores')
                ->setType(static::COLUMN_TYPE_TEXT)
                ->setSortable(false)
                ->setHideable(false)
        )->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_STATUS)
                ->setTitle('Status')
                ->setType(static::COLUMN_TYPE_TEXT)
                ->setSortable(true)
                ->setHideable(false)
        )->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_VALID_FROM)
                ->setTitle('Valid From')
                ->setType(static::COLUMN_TYPE_DATE)
                ->addTypeOption('format', static::PATTERN_DATE_FORMAT)
                ->setSortable(true)
                ->setHideable(false)
        )->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_VALID_TO)
                ->setTitle('Valid To')
                ->setType(static::COLUMN_TYPE_DATE)
                ->addTypeOption('format', static::PATTERN_DATE_FORMAT)
                ->setSortable(true)
                ->setHideable(false)
        )->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_OFFERS)
                ->setTitle('Offers')
                ->setType(static::COLUMN_TYPE_TEXT)
                ->setSortable(true)
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
        foreach ($this->productTableFilters as $productTableFilter) {
            $guiTableConfigurationTransfer->addFilter($productTableFilter->getFilter());
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
     * @return \Generated\Shared\Transfer\ProductCriteriaFilterTransfer
     */
    protected function buildProductCriteriaFilterTransfer(): ProductCriteriaFilterTransfer
    {
        return $this->productCriteriaFilterBuilder
            ->setSearchTerm($this->searchTerm)
            ->setPage($this->page)
            ->setPageSize($this->pageSize)
            ->setSortColumn($this->sortColumn)
            ->setSortDirection($this->sortDirection)
            ->setFilters($this->filters)
            ->build();
    }

    /**
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
            ->addTypeOption('url', 'https://path-to-create-offer-action/${row.sku}')
            ->addTypeOption('icon', 'icon-name');

        $guiTableConfigurationTransfer->addRowAction($guiTableRowActionTransfer);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addSearchToConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer->addSearchOption('placeholder', static::SEARCH_PLACEHOLDER);

        return $guiTableConfigurationTransfer;
    }
}
