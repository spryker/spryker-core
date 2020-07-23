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
use Generated\Shared\Transfer\GuiTableRowActionsConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableRowActionTransfer;
use Generated\Shared\Transfer\GuiTableSearchConfigurationTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\DataProvider\ProductTableDataProvider;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface;

class ProductGuiTableConfigurationProvider implements ProductGuiTableConfigurationProviderInterface
{
    public const COL_KEY_NAME = 'name';
    public const COL_KEY_SKU = 'sku';
    public const COL_KEY_IMAGE = 'image';
    public const COL_KEY_STORES = 'stores';
    public const COL_KEY_STATUS = 'status';
    public const COL_KEY_OFFERS = 'offers';
    public const COL_KEY_VALID_FROM = 'validFrom';
    public const COL_KEY_VALID_TO = 'validTo';

    protected const SEARCH_PLACEHOLDER = 'Search by SKU, Name';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\CreateOfferController::tableDataAction()
     */
    protected const DATA_URL = '/product-offer-merchant-portal-gui/create-offer/table-data';

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @var \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected $guiTableConfigurationBuilder;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
    ) {
        $this->translatorFacade = $translatorFacade;
        $this->guiTableConfigurationBuilder = $guiTableConfigurationBuilder;
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
        $guiTableConfigurationTransfer = $this->addSearchToConfiguration($guiTableConfigurationTransfer);
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
            $this->guiTableConfigurationBuilder->createColumnText(static::COL_KEY_SKU, 'SKU', true, false),
            $this->guiTableConfigurationBuilder->createColumnImage(static::COL_KEY_IMAGE, 'Image', false, false),
            $this->guiTableConfigurationBuilder->createColumnText(static::COL_KEY_NAME, 'Name', true, false),
            $this->guiTableConfigurationBuilder->createColumnChips(static::COL_KEY_STORES, 'Stores', false, true, [
                'limit' => 3,
                'typeOptions' => [
                    'color' => 'grey',
                ],
            ]),
            $this->guiTableConfigurationBuilder->createColumnChip(static::COL_KEY_STATUS, 'Status', true, false, [
                'color' => 'grey',
            ], [
                'color' => [$this->translatorFacade->trans(ProductTableDataProvider::COLUMN_DATA_STATUS_ACTIVE) => 'green'],
            ]),
            $this->guiTableConfigurationBuilder->createColumnDate(static::COL_KEY_VALID_FROM, 'Valid From', true, false),
            $this->guiTableConfigurationBuilder->createColumnDate(static::COL_KEY_VALID_TO, 'Valid To', true, false),
            $this->guiTableConfigurationBuilder->createColumnText(static::COL_KEY_OFFERS, 'Offers', true, false),
        ]);

        $guiTableConfigurationTransfer->setColumns($columns);

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
            $this->guiTableConfigurationBuilder->createFilterSelect('hasOffers', 'Offers', false, [
                '1' => 'With Offers',
                '0' => 'Without Offers',
            ]),
            $this->guiTableConfigurationBuilder->createFilterSelect('isActive', 'Status', false, [
                '1' => 'Active',
                '0' => 'Inactive',
            ]),
        ]);
        $guiTableConfigurationTransfer->setFilters(
            (new GuiTableFiltersConfigurationTransfer())->setItems($filters)
        );

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
            ->addTypeOption(
                'url',
                sprintf(
                    '/product-offer-merchant-portal-gui/create-product-offer?product-id=${row.%s}',
                    ProductConcreteTransfer::ID_PRODUCT_CONCRETE
                )
            );

        $guiTableConfigurationTransfer->setRowActions(
            (new GuiTableRowActionsConfigurationTransfer())
                ->addAction($guiTableRowActionTransfer)
                ->setClick('create-offer')
        );

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addSearchToConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer->setSearch(
            (new GuiTableSearchConfigurationTransfer())
                ->addSearchOption('placeholder', static::SEARCH_PLACEHOLDER)
        );

        return $guiTableConfigurationTransfer;
    }
}
