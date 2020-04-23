<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable;

use Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataTransfer;
use Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\AbstractTable;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaBuilder\ProductOfferTableCriteriaBuilderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\DataProvider\ProductOfferTableDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface;

class ProductOfferTable extends AbstractTable
{
    public const COL_KEY_OFFER_REFERENCE = 'offerReference';
    public const COL_KEY_MERCHANT_SKU = 'merchantSku';
    public const COL_KEY_CONCRETE_SKU = 'concreteSku';
    public const COL_KEY_IMAGE = 'image';
    public const COL_KEY_PRODUCT_NAME = 'productName';
    public const COL_KEY_STORES = 'stores';
    public const COL_KEY_STOCK = 'stock';
    public const COL_KEY_VISIBILITY = 'visibility';
    public const COL_KEY_VALID_FROM = 'validFrom';
    public const COL_KEY_VALID_TO = 'validTo';
    public const COL_KEY_CREATED_AT = 'createdAt';
    public const COL_KEY_UPDATED_AT = 'updatedAt';

    protected const PATTERN_DATE_FORMAT = 'dd.MM.y';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\ProductTableController::getDataAction()
     */
    protected const DATA_URL = '/product-offer-merchant-portal-gui/offers/get-table-data';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\DataProvider\ProductOfferTableDataProviderInterface
     */
    protected $productOfferTableDataProvider;

    /**
     * @var array|\Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface[]
     */
    protected $productOfferTableFilterDataProviders;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaBuilder\ProductOfferTableCriteriaBuilderInterface
     */
    protected $productOfferTableCriteriaBuilder;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\DataProvider\ProductOfferTableDataProviderInterface $productTableDataProvider
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface[] $productOfferTableFilterDataProviders
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\CriteriaBuilder\ProductOfferTableCriteriaBuilderInterface $productOfferTableCriteriaBuilder
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService,
        ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        ProductOfferTableDataProviderInterface $productTableDataProvider,
        array $productOfferTableFilterDataProviders,
        ProductOfferTableCriteriaBuilderInterface $productOfferTableCriteriaBuilder
    ) {
        parent::__construct($utilEncodingService, $translatorFacade);
        $this->productOfferTableDataProvider = $productTableDataProvider;
        $this->productOfferTableFilterDataProviders = $productOfferTableFilterDataProviders;
        $this->productOfferTableCriteriaBuilder = $productOfferTableCriteriaBuilder;
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer
     */
    protected function provideTableData(): GuiTableDataTransfer
    {
        $productTableCriteriaTransfer = $this->buildProductOfferTableCriteriaTransfer();

        return $this->productOfferTableDataProvider->getProductOfferTableData($productTableCriteriaTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function buildTableConfiguration(): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer = new GuiTableConfigurationTransfer();
        $guiTableConfigurationTransfer = $this->addColumnsToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->addFiltersToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->addSearchOptionsToConfiguration($guiTableConfigurationTransfer);
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
                ->setId(static::COL_KEY_OFFER_REFERENCE)
                ->setTitle('Reference')
                ->setType(static::COLUMN_TYPE_TEXT)
                ->setSortable(true)
                ->setHideable(false)
        )->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_MERCHANT_SKU)
                ->setTitle('Merchant SKU')
                ->setType(static::COLUMN_TYPE_TEXT)
                ->setSortable(true)
                ->setHideable(true)
        )->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_CONCRETE_SKU)
                ->setTitle('SKU')
                ->setType(static::COLUMN_TYPE_TEXT)
                ->setSortable(true)
                ->setHideable(true)
        )->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_IMAGE)
                ->setTitle('Image')
                ->setType(static::COLUMN_TYPE_IMAGE)
                ->setSortable(false)
                ->setHideable(true)
        )->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_PRODUCT_NAME)
                ->setTitle('Name')
                ->setType(static::COLUMN_TYPE_TEXT)
                ->setSortable(true)
                ->setHideable(true)
        )->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_STORES)
                ->setTitle('Stores')
                ->setType(static::COLUMN_TYPE_CHIP)
                ->setSortable(false)
                ->setHideable(true)
        )->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_STOCK)
                ->setTitle('Stock')
                ->setType(static::COLUMN_TYPE_CHIP)
                ->setSortable(true)
                ->setHideable(true)
        )->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_VISIBILITY)
                ->setTitle('Visibility')
                ->setType(static::COLUMN_TYPE_CHIP)
                ->setSortable(true)
                ->setHideable(true)
        )->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_VALID_FROM)
                ->setTitle('Valid From')
                ->setType(static::COLUMN_TYPE_DATE)
                ->addTypeOption('format', static::PATTERN_DATE_FORMAT)
                ->setSortable(true)
                ->setHideable(true)
        )->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_VALID_TO)
                ->setTitle('Valid To')
                ->setType(static::COLUMN_TYPE_DATE)
                ->addTypeOption('format', static::PATTERN_DATE_FORMAT)
                ->setSortable(true)
                ->setHideable(true)
        )->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_CREATED_AT)
                ->setTitle('Created')
                ->setType(static::COLUMN_TYPE_DATE)
                ->addTypeOption('format', static::PATTERN_DATE_FORMAT)
                ->setSortable(true)
                ->setHideable(true)
        )->addColumn(
            (new GuiTableColumnConfigurationTransfer())
                ->setId(static::COL_KEY_UPDATED_AT)
                ->setTitle('Updated')
                ->setType(static::COLUMN_TYPE_DATE)
                ->addTypeOption('format', static::PATTERN_DATE_FORMAT)
                ->setSortable(true)
                ->setHideable(true)
        );

        return $guiTableConfigurationTransfer;
    }

    /**
     * @return string
     */
    protected function getDefaultSortColumnKey(): string
    {
        return static::COL_KEY_OFFER_REFERENCE;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer
     */
    protected function buildProductOfferTableCriteriaTransfer(): ProductOfferTableCriteriaTransfer
    {
        return $this->productOfferTableCriteriaBuilder
            ->setSearchTerm($this->searchTerm)
            ->setPage($this->page)
            ->setPageSize($this->pageSize)
            ->setSorting($this->sorting)
            ->setFilters($this->filters)
            ->build();
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addSearchOptionsToConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer->addSearchOption('placeholder', 'Search');

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addFiltersToConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        foreach ($this->productOfferTableFilterDataProviders as $productOfferTableFilterDataProvider) {
            $guiTableConfigurationTransfer->addFilter($productOfferTableFilterDataProvider->getFilterData());
        }

        return $guiTableConfigurationTransfer;
    }
}
