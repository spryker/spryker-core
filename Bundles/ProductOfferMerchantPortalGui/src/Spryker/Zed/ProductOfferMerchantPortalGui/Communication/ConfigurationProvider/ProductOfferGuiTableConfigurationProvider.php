<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataSourceConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableFiltersConfigurationTransfer;
use Generated\Shared\Transfer\GuiTablePaginationConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableRowActionsConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableRowActionTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\GuiTable\Communication\ConfigurationProvider\AbstractGuiTableConfigurationProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\ProductOfferTableDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;

class ProductOfferGuiTableConfigurationProvider extends AbstractGuiTableConfigurationProvider implements GuiTableConfigurationProviderInterface
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

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\OffersController::tableDataAction()
     */
    protected const DATA_URL = '/product-offer-merchant-portal-gui/offers/table-data';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToStoreFacadeInterface $storeFacade,
        ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
    ) {
        $this->storeFacade = $storeFacade;
        $this->translatorFacade = $translatorFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer = new GuiTableConfigurationTransfer();
        $guiTableConfigurationTransfer = $this->addColumnsToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->addFiltersToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->addRowActionsToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->addPaginationToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer->setDefaultSortColumn($this->getDefaultSortColumnKey());
        $guiTableConfigurationTransfer->setDataSource(
            (new GuiTableDataSourceConfigurationTransfer())->setUrl(static::DATA_URL)
        );

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
            $this->createColumnChips(static::COL_KEY_STORES, 'Stores', false, true, [
                'limit' => 2,
                'typeOptions' => [
                    'color' => 'grey',
                ],
            ]),
            $this->createColumnChip(static::COL_KEY_STOCK, 'Stock', true, true, [
                'color' => 'grey',
            ], [
                'color' => [0 => 'red'],
            ]),
            $this->createColumnChip(static::COL_KEY_VISIBILITY, 'Visibility', true, true, [
                'color' => 'grey',
            ], [
                'color' => [$this->translatorFacade->trans(ProductOfferTableDataProvider::COLUMN_DATA_VISIBILITY_ONLINE) => 'green'],
            ]),
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
        $guiTableConfigurationTransfer->setFilters(
            (new GuiTableFiltersConfigurationTransfer())->setItems($filters)
        );

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

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addRowActionsToConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableRowActionTransfer = (new GuiTableRowActionTransfer())
            ->setId('update-offer')
            ->setTitle('Manage Offer')
            ->setType('form-overlay')
            ->addTypeOption(
                'url',
                sprintf(
                    '/product-offer-merchant-portal-gui/update-product-offer?product-offer-id=${row.%s}',
                    ProductOfferTransfer::ID_PRODUCT_OFFER
                )
            );

        $guiTableConfigurationTransfer->setRowActions(
            (new GuiTableRowActionsConfigurationTransfer())
                ->addAction($guiTableRowActionTransfer)
                ->setClick('update-offer')
        );

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addPaginationToConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer->setPagination(
            (new GuiTablePaginationConfigurationTransfer())->setDefaultSize(25)
        );

        return $guiTableConfigurationTransfer;
    }
}
