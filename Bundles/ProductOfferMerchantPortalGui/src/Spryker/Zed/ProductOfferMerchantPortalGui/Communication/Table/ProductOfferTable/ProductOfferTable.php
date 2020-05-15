<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\AbstractTable;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\TableDataProviderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;
use Symfony\Component\HttpFoundation\Request;

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
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\OffersController::tableDataAction()
     */
    protected const DATA_URL = '/product-offer-merchant-portal-gui/offers/table-data';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\TableDataProviderInterface
     */
    protected $productOfferTableDataProvider;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\TableDataProviderInterface $productOfferTableDataProvider
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        TableDataProviderInterface $productOfferTableDataProvider,
        ProductOfferMerchantPortalGuiToStoreFacadeInterface $storeFacade
    ) {
        parent::__construct($translatorFacade);
        $this->productOfferTableDataProvider = $productOfferTableDataProvider;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\GuiTableDataTransfer
     */
    protected function provideTableData(Request $request): GuiTableDataTransfer
    {
        return $this->productOfferTableDataProvider->getData($request, $this->buildTableConfiguration());
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
        $columns = new ArrayObject([
            $this->createColumnText(static::COL_KEY_OFFER_REFERENCE, 'Reference', true, false),
            $this->createColumnText(static::COL_KEY_MERCHANT_SKU, 'Merchant SKU', true, true),
            $this->createColumnText(static::COL_KEY_CONCRETE_SKU, 'SKU', true, true),
            $this->createColumnImage(static::COL_KEY_IMAGE, 'Image', false, true),
            $this->createColumnText(static::COL_KEY_PRODUCT_NAME, 'Name', true, true),
            $this->createColumnChip(static::COL_KEY_STORES, 'Stores', false, true),
            $this->createColumnChip(static::COL_KEY_STOCK, 'Stock', true, true),
            $this->createColumnChip(static::COL_KEY_VISIBILITY, 'Visibility', true, true),
            $this->createColumnDate(static::COL_KEY_VALID_FROM, 'Valid From', true, true),
            $this->createColumnDate(static::COL_KEY_VALID_TO, 'Valid To', true, true),
            $this->createColumnDate(static::COL_KEY_CREATED_AT, 'Created', true, true),
            $this->createColumnDate(static::COL_KEY_UPDATED_AT, 'Updated', true, true),
        ]);

        $guiTableConfigurationTransfer->setColumns($columns);

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
        $filters = new ArrayObject([
            $this->createFilterSelect('hasStock', 'Stock', false, [
                '1' => 'Has stock',
                '0' => 'Out of stock',
            ]),
            $this->createFilterSelect('isActive', 'Visibility', false, [
                '1' => 'Online',
                '0' => 'Offline',
            ]),
            $this->createFilterSelect('inStores', 'Stores', true, $this->getStoreOptions()),
            $this->createFilterDateRange('createdAt', 'Created'),
            $this->createFilterDateRange('updatedAt', 'Updated'),
            $this->createFilterDateRange('validity', 'Validity'),
        ]);
        $guiTableConfigurationTransfer->setFilters($filters);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @return string[]
     */
    protected function getStoreOptions(): array
    {
        $storeTransfers = $this->storeFacade->getAllStores();

        $storeOptions = [];
        foreach ($storeTransfers as $storeTransfer) {
            $storeOptions[$storeTransfer->getIdStore()] = $storeTransfer->getName();
        }

        return $storeOptions;
    }
}
