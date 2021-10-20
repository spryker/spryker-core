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
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;

class ProductOfferGuiTableConfigurationProvider implements GuiTableConfigurationProviderInterface
{
    /**
     * @var string
     */
    public const COL_KEY_OFFER_REFERENCE = 'offerReference';

    /**
     * @var string
     */
    public const COL_KEY_MERCHANT_SKU = 'merchantSku';

    /**
     * @var string
     */
    public const COL_KEY_CONCRETE_SKU = 'concreteSku';

    /**
     * @var string
     */
    public const COL_KEY_IMAGE = 'image';

    /**
     * @var string
     */
    public const COL_KEY_PRODUCT_NAME = 'productName';

    /**
     * @var string
     */
    public const COL_KEY_STORES = 'stores';

    /**
     * @var string
     */
    public const COL_KEY_STOCK = 'stock';

    /**
     * @var string
     */
    public const COL_KEY_STATUS = 'status';

    /**
     * @var string
     */
    public const COL_KEY_APPROVAL_STATUS = 'approvalStatus';

    /**
     * @var string
     */
    public const COL_KEY_VALID_FROM = 'validFrom';

    /**
     * @var string
     */
    public const COL_KEY_VALID_TO = 'validTo';

    /**
     * @var string
     */
    public const COL_KEY_CREATED_AT = 'createdAt';

    /**
     * @var string
     */
    public const COL_KEY_UPDATED_AT = 'updatedAt';

    /**
     * @var string
     */
    public const COLUMN_DATA_STATUS_ACTIVE = 'Active';

    /**
     * @var string
     */
    public const COLUMN_DATA_STATUS_INACTIVE = 'Inactive';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\ProductOfferGuiTableDataProvider::COLUMN_DATA_APPROVAL_STATUS_WAITING_FOR_APPROVAL
     * @var string
     */
    protected const COLUMN_DATA_APPROVAL_STATUS_WAITING_FOR_APPROVAL = 'Pending';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_WAITING_FOR_APPROVAL
     * @var string
     */
    protected const APPROVAL_STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     * @var string
     */
    protected const APPROVAL_STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Shared\ProductOffer\ProductOfferConfig::STATUS_DENIED
     * @var string
     */
    protected const APPROVAL_STATUS_DENIED = 'denied';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\ProductOffersController::tableDataAction()
     * @var string
     */
    protected const DATA_URL = '/product-offer-merchant-portal-gui/product-offers/table-data';

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
            ->addColumnListChip(static::COL_KEY_STORES, 'Stores', false, true, 2, 'gray')
            ->addColumnChip(static::COL_KEY_STOCK, 'Stock', true, true, 'green', [0 => 'red'])
            ->addColumnChip(static::COL_KEY_STATUS, 'Status', true, true, 'gray', [
                $this->translatorFacade->trans(static::COLUMN_DATA_STATUS_ACTIVE) => 'green',
            ])
            ->addColumnChip(static::COL_KEY_APPROVAL_STATUS, 'Approval Status', true, true, 'green', [
                $this->translatorFacade->trans(static::COLUMN_DATA_APPROVAL_STATUS_WAITING_FOR_APPROVAL) => 'yellow',
                $this->translatorFacade->trans(static::APPROVAL_STATUS_DENIED) => 'red',
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
            ->addFilterSelect('isActive', 'Status', false, [
                '1' => static::COLUMN_DATA_STATUS_ACTIVE,
                '0' => static::COLUMN_DATA_STATUS_INACTIVE,
            ])
            ->addFilterSelect('inStores', 'Stores', true, $this->getStoreOptions())
            ->addFilterSelect('approvalStatus', 'Approval Status', false, $this->getApprovalStatusOptions())
            ->addFilterDateRange('createdAt', 'Created')
            ->addFilterDateRange('updatedAt', 'Updated')
            ->addFilterDateRange('validity', 'Validity');

        return $guiTableConfigurationBuilder;
    }

    /**
     * @return array<string>
     */
    protected function getStoreOptions(): array
    {
        $storeTransfers = $this->storeFacade->getAllStores();

        $storeOptions = [];
        foreach ($storeTransfers as $storeTransfer) {
            $idStore = $storeTransfer->getIdStoreOrFail();
            $storeName = $storeTransfer->getNameOrFail();

            $storeOptions[$idStore] = $storeName;
        }

        return $storeOptions;
    }

    /**
     * @return array<string>
     */
    protected function getApprovalStatusOptions(): array
    {
        return [
            static::APPROVAL_STATUS_APPROVED => static::APPROVAL_STATUS_APPROVED,
            static::APPROVAL_STATUS_WAITING_FOR_APPROVAL => static::COLUMN_DATA_APPROVAL_STATUS_WAITING_FOR_APPROVAL,
            static::APPROVAL_STATUS_DENIED => static::APPROVAL_STATUS_DENIED,
        ];
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addRowActions(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addRowActionDrawerAjaxForm(
            'update-offer',
            'Manage Offer',
            sprintf(
                '/product-offer-merchant-portal-gui/update-product-offer?product-offer-id=${row.%s}',
                ProductOfferTransfer::ID_PRODUCT_OFFER,
            ),
        )->setRowClickAction('update-offer');

        return $guiTableConfigurationBuilder;
    }
}
