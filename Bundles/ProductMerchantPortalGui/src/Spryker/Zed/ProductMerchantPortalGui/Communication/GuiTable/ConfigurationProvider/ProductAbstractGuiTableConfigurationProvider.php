<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface;

class ProductAbstractGuiTableConfigurationProvider implements ProductAbstractGuiTableConfigurationProviderInterface
{
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
    public const COL_KEY_NAME = 'name';

    /**
     * @var string
     */
    public const COL_KEY_SUPER_ATTRIBUTES = 'superAttributes';

    /**
     * @var string
     */
    public const COL_KEY_VARIANTS = 'variants';

    /**
     * @var string
     */
    public const COL_KEY_CATEGORIES = 'categories';

    /**
     * @var string
     */
    public const COL_KEY_STORES = 'stores';

    /**
     * @var string
     */
    public const COL_KEY_VISIBILITY = 'visibility';

    /**
     * @var string
     */
    public const COL_KEY_APPROVAL = 'approval';

    /**
     * @var string
     */
    public const COLUMN_DATA_VISIBILITY_ONLINE = 'Active';

    /**
     * @var string
     */
    public const COLUMN_DATA_VISIBILITY_OFFLINE = 'Inactive';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_WAITING_FOR_APPROVAL
     *
     * @var string
     */
    protected const STATUS_WAITING_FOR_APPROVAL = 'waiting_for_approval';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_DENIED
     *
     * @var string
     */
    protected const STATUS_DENIED = 'denied';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_DRAFT
     *
     * @var string
     */
    protected const STATUS_DRAFT = 'draft';

    /**
     * @var string
     */
    protected const SEARCH_PLACEHOLDER = 'Search by SKU, Name';

    /**
     * @var string
     */
    protected const TITLE_ROW_ACTION_UPDATE_PRODUCT = 'Manage Product';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\UpdateProductAbstractController::indexAction()
     *
     * @var string
     */
    protected const URL_ROW_ACTION_UPDATE_PRODUCT = '/product-merchant-portal-gui/update-product-abstract?product-abstract-id=${row.%s}';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\ProductsController::tableDataAction()
     *
     * @var string
     */
    protected const DATA_URL = '/product-merchant-portal-gui/products/table-data';

    /**
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected $guiTableFactory;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\CategoryFilterOptionsProviderInterface
     */
    protected $categoryFilterOptionsProvider;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\StoreFilterOptionsProviderInterface
     */
    protected $storeFilterOptionsProvider;

    /**
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\CategoryFilterOptionsProviderInterface $categoryFilterOptionsProvider
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\StoreFilterOptionsProviderInterface $storeFilterOptionsProvider
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        GuiTableFactoryInterface $guiTableFactory,
        CategoryFilterOptionsProviderInterface $categoryFilterOptionsProvider,
        StoreFilterOptionsProviderInterface $storeFilterOptionsProvider,
        ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
    ) {
        $this->guiTableFactory = $guiTableFactory;
        $this->categoryFilterOptionsProvider = $categoryFilterOptionsProvider;
        $this->storeFilterOptionsProvider = $storeFilterOptionsProvider;
        $this->translatorFacade = $translatorFacade;
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
            ->setSearchPlaceholder(static::SEARCH_PLACEHOLDER)
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
        $guiTableConfigurationBuilder->addColumnText(static::COL_KEY_SKU, 'SKU', true, false)
            ->addColumnImage(static::COL_KEY_IMAGE, 'Image', false, true, static::COL_KEY_NAME)
            ->addColumnText(static::COL_KEY_NAME, 'Name', true, false)
            ->addColumnListChip(static::COL_KEY_SUPER_ATTRIBUTES, 'Super Attributes', false, true, 2, 'gray')
            ->addColumnChip(static::COL_KEY_VARIANTS, 'Variants', true, true, 'gray')
            ->addColumnListChip(static::COL_KEY_CATEGORIES, 'Categories', false, true, 2, 'gray')
            ->addColumnListChip(static::COL_KEY_STORES, 'Stores', false, true, 2, 'gray')
            ->addColumnChip(static::COL_KEY_VISIBILITY, 'Status', true, true, 'gray', [
                $this->translatorFacade->trans(static::COLUMN_DATA_VISIBILITY_ONLINE) => 'green',
            ])
            ->addColumnChip(static::COL_KEY_APPROVAL, 'Approval Status', true, true, 'gray', [
                $this->translatorFacade->trans(static::STATUS_WAITING_FOR_APPROVAL) => 'yellow',
                $this->translatorFacade->trans(static::STATUS_DENIED) => 'red',
                $this->translatorFacade->trans(static::STATUS_APPROVED) => 'green',
                $this->translatorFacade->trans(static::STATUS_DRAFT) => 'orange',
            ]);

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addFilters(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addFilterTreeSelect(
            'inCategories',
            'Categories',
            true,
            $this->categoryFilterOptionsProvider->getCategoryFilterOptionsTree(),
        )
        ->addFilterSelect('isVisible', 'Status', false, [
            '1' => static::COLUMN_DATA_VISIBILITY_ONLINE,
            '0' => static::COLUMN_DATA_VISIBILITY_OFFLINE,
        ])
        ->addFilterSelect('inStores', 'Stores', true, $this->storeFilterOptionsProvider->getStoreOptions())
        ->addFilterSelect('inApprovalStatuses', 'Approval Status', true, [
            static::STATUS_APPROVED => static::STATUS_APPROVED,
            static::STATUS_DENIED => static::STATUS_DENIED,
            static::STATUS_WAITING_FOR_APPROVAL => static::STATUS_WAITING_FOR_APPROVAL,
            static::STATUS_DRAFT => static::STATUS_DRAFT,
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
            'update-product',
            static::TITLE_ROW_ACTION_UPDATE_PRODUCT,
            sprintf(
                static::URL_ROW_ACTION_UPDATE_PRODUCT,
                ProductAbstractTransfer::ID_PRODUCT_ABSTRACT,
            ),
        )->setRowClickAction('update-product');

        return $guiTableConfigurationBuilder;
    }
}
