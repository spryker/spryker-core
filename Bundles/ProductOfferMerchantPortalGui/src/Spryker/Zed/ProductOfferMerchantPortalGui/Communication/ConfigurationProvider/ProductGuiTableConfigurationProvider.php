<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;

class ProductGuiTableConfigurationProvider implements GuiTableConfigurationProviderInterface
{
    /**
     * @var string
     */
    public const COL_KEY_NAME = 'name';

    /**
     * @var string
     */
    public const COL_KEY_SKU = 'sku';

    /**
     * @var string
     */
    public const COL_KEY_IMAGE = 'image';

    /**
     * @var string
     */
    public const COL_KEY_STORES = 'stores';

    /**
     * @var string
     */
    public const COL_KEY_STATUS = 'status';

    /**
     * @var string
     */
    public const COL_KEY_OFFERS = 'offers';

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
    public const COLUMN_DATA_STATUS_ACTIVE = 'Online';

    /**
     * @var string
     */
    public const COLUMN_DATA_STATUS_INACTIVE = 'Offline';

    /**
     * @var string
     */
    protected const SEARCH_PLACEHOLDER = 'Search by SKU, Name';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\ProductListController::tableDataAction()
     *
     * @var string
     */
    protected const DATA_URL = '/product-offer-merchant-portal-gui/product-list/table-data';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade;

    /**
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected GuiTableFactoryInterface $guiTableFactory;

    /**
     * @var array<\Spryker\Zed\ProductOfferMerchantPortalGuiExtension\Dependency\Plugin\ProductTableExpanderPluginInterface>
     */
    protected array $productTableExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     * @param array<\Spryker\Zed\ProductOfferMerchantPortalGuiExtension\Dependency\Plugin\ProductTableExpanderPluginInterface> $productTableExpanderPlugins
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        GuiTableFactoryInterface $guiTableFactory,
        array $productTableExpanderPlugins
    ) {
        $this->translatorFacade = $translatorFacade;
        $this->guiTableFactory = $guiTableFactory;
        $this->productTableExpanderPlugins = $productTableExpanderPlugins;
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
            ->setTableTitle('List of Products')
            ->setDataSourceUrl(static::DATA_URL)
            ->setSearchPlaceholder(static::SEARCH_PLACEHOLDER);

        $guiTableConfigurationTransfer = $guiTableConfigurationBuilder->createConfiguration();
        $guiTableConfigurationTransfer = $this->executeProductTableExpanderPlugins($guiTableConfigurationTransfer);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addColumns(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addColumnText(static::COL_KEY_SKU, 'SKU', true, false)
            ->addColumnImage(static::COL_KEY_IMAGE, 'Image', false, true, static::COL_KEY_NAME)
            ->addColumnText(static::COL_KEY_NAME, 'Name', true, false)
            ->addColumnListChip(static::COL_KEY_STORES, 'Stores', false, true, 2, 'gray')
            ->addColumnChip(static::COL_KEY_STATUS, 'Status', true, true, 'gray', [
                $this->translatorFacade->trans(static::COLUMN_DATA_STATUS_ACTIVE) => 'green',
            ])
            ->addColumnDate(static::COL_KEY_VALID_FROM, 'Valid From', true, true)
            ->addColumnDate(static::COL_KEY_VALID_TO, 'Valid To', true, true)
            ->addColumnText(static::COL_KEY_OFFERS, 'Offers', true, true);

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addFilters(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addFilterSelect('hasOffers', 'Offers', false, [
                '1' => 'With Offers',
                '0' => 'Without Offers',
            ])
            ->addFilterSelect('isActive', 'Status', false, [
                '1' => static::COLUMN_DATA_STATUS_ACTIVE,
                '0' => static::COLUMN_DATA_STATUS_INACTIVE,
            ]);

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addRowActions(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addRowActionDrawerAjaxForm(
            'create-offer',
            'Create Offer',
            sprintf(
                '/product-offer-merchant-portal-gui/create-product-offer?product-id=${row.%s}',
                ProductConcreteTransfer::ID_PRODUCT_CONCRETE,
            ),
        )->setRowClickAction('create-offer');

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function executeProductTableExpanderPlugins(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableConfigurationTransfer {
        foreach ($this->productTableExpanderPlugins as $productTableExpanderPlugin) {
            $guiTableConfigurationTransfer = $productTableExpanderPlugin->expandConfiguration($guiTableConfigurationTransfer);
        }

        return $guiTableConfigurationTransfer;
    }
}
