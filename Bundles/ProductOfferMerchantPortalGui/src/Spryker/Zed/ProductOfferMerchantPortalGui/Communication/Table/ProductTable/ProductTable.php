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
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\AbstractTable;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\CriteriaBuilder\ProductTableCriteriaBuilderInterface;
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
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\CreateOfferController::getTableDataAction()
     */
    protected const DATA_URL = '/product-offer-merchant-portal-gui/create-offer/get-table-data';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\DataProvider\ProductTableDataProviderInterface
     */
    protected $productTableDataProvider;

    /**
     * @var array|\Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\Filter\ProductTableFilterDataProviderInterface[]
     */
    protected $productTableFilterDataProviders;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\CriteriaBuilder\ProductTableCriteriaBuilderInterface
     */
    protected $productTableCriteriaBuilder;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\DataProvider\ProductTableDataProviderInterface $productTableDataProvider
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\Filter\ProductTableFilterDataProviderInterface[] $productTableFilterDataProviders
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\CriteriaBuilder\ProductTableCriteriaBuilderInterface $productTableCriteriaBuilder
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService,
        ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        ProductTableDataProviderInterface $productTableDataProvider,
        array $productTableFilterDataProviders,
        ProductTableCriteriaBuilderInterface $productTableCriteriaBuilder
    ) {
        parent::__construct($utilEncodingService, $translatorFacade);
        $this->productTableDataProvider = $productTableDataProvider;
        $this->productTableFilterDataProviders = $productTableFilterDataProviders;
        $this->productTableCriteriaBuilder = $productTableCriteriaBuilder;
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer
     */
    protected function provideTableData(): GuiTableDataTransfer
    {
        $productTableCriteriaTransfer = $this->buildProductTableCriteriaTransfer();

        return $this->productTableDataProvider->getProductTableData($productTableCriteriaTransfer);
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
