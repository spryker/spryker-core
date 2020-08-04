<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\ProductOfferTableDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;

class ProductOfferGuiTableConfigurationProvider implements ProductOfferGuiTableConfigurationProviderInterface
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
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected $guiTableFactory;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToStoreFacadeInterface $storeFacade,
        ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        GuiTableFactoryInterface $guiTableFactory
    ) {
        $this->storeFacade = $storeFacade;
        $this->translatorFacade = $translatorFacade;
        $this->guiTableFactory = $guiTableFactory;
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();

        $guiTableConfigurationBuilder = $this->addColumns($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addFilters($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addRowActions($guiTableConfigurationBuilder);

        $guiTableConfigurationBuilder
            ->setDataSourceUrl(static::DATA_URL)
            ->setDefaultPageSize(25);

        return $guiTableConfigurationBuilder->createConfiguration();
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addColumns(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addColumnText(static::COL_KEY_OFFER_REFERENCE, 'Reference', true, false)
            ->addColumnText(static::COL_KEY_MERCHANT_SKU, 'Merchant SKU', true, true)
            ->addColumnText(static::COL_KEY_CONCRETE_SKU, 'SKU', true, true)
            ->addColumnImage(static::COL_KEY_IMAGE, 'Image', false, true)
            ->addColumnText(static::COL_KEY_PRODUCT_NAME, 'Name', true, true)
            ->addColumnChips(static::COL_KEY_STORES, 'Stores', false, true, 3, 'grey')
            ->addColumnChip(static::COL_KEY_STOCK, 'Stock', true, true, 'green', [0 => 'red'])
            ->addColumnChip(static::COL_KEY_VISIBILITY, 'Visibility', true, true, 'grey', [
                $this->translatorFacade->trans(ProductOfferTableDataProvider::COLUMN_DATA_VISIBILITY_ONLINE) => 'green',
            ])
            ->addColumnDate(static::COL_KEY_VALID_FROM, 'Valid From', true, true)
            ->addColumnDate(static::COL_KEY_VALID_TO, 'Valid To', true, true)
            ->addColumnDate(static::COL_KEY_CREATED_AT, 'Created', true, true)
            ->addColumnDate(static::COL_KEY_UPDATED_AT, 'Updated', true, true);

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addFilters(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addFilterSelect('hasStock', 'Stock', false, [
                '1' => 'Has stock',
                '0' => 'Out of stock',
            ])
            ->addFilterSelect('isActive', 'Visibility', false, [
                '1' => 'Online',
                '0' => 'Offline',
            ])
            ->addFilterSelect('inStores', 'Stores', true, $this->getStoreOptions())
            ->addFilterDateRange('createdAt', 'Created')
            ->addFilterDateRange('updatedAt', 'Updated')
            ->addFilterDateRange('validity', 'Validity');

        return $guiTableConfigurationBuilder;
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
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addRowActions(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addRowActionOpenFormOverlay(
            'update-offer',
            'Manage Offer',
            sprintf(
                '/product-offer-merchant-portal-gui/update-product-offer?product-offer-id=${row.%s}',
                ProductOfferTransfer::ID_PRODUCT_OFFER
            )
        )->setRowClickAction('update-offer');

        return $guiTableConfigurationBuilder;
    }
}
