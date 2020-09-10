<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface;

class ProductAbstractGuiTableConfigurationProvider implements ProductAbstractGuiTableConfigurationProviderInterface
{
    public const COL_KEY_SKU = 'sku';
    public const COL_KEY_IMAGE = 'image';
    public const COL_KEY_NAME = 'name';
    public const COL_KEY_SUPER_ATTRIBUTES = 'superAttributes';
    public const COL_KEY_VARIANTS = 'variants';
    public const COL_KEY_CATEGORIES = 'categories';
    public const COL_KEY_STORES = 'stores';
    public const COL_KEY_VISIBILITY = 'visibility';

    public const COLUMN_DATA_VISIBILITY_ONLINE = 'Online';
    public const COLUMN_DATA_VISIBILITY_OFFLINE = 'Offline';

    protected const SEARCH_PLACEHOLDER = 'Search by SKU, Name';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\ProductsController::tableDataAction()
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
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\CategoryFilterOptionsProviderInterface
     */
    protected $categoryFilterOptionsProvider;

    /**
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\CategoryFilterOptionsProviderInterface $categoryFilterOptionsProvider
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        GuiTableFactoryInterface $guiTableFactory,
        CategoryFilterOptionsProviderInterface $categoryFilterOptionsProvider,
        ProductMerchantPortalGuiToStoreFacadeInterface $storeFacade,
        ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
    ) {
        $this->guiTableFactory = $guiTableFactory;
        $this->categoryFilterOptionsProvider = $categoryFilterOptionsProvider;
        $this->storeFacade = $storeFacade;
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
            ->addColumnImage(static::COL_KEY_IMAGE, 'Image', false, true)
            ->addColumnText(static::COL_KEY_NAME, 'Name', true, false)
            ->addColumnListChip(static::COL_KEY_SUPER_ATTRIBUTES, 'Super Attributes', false, true, 2, 'grey')
            ->addColumnChip(static::COL_KEY_VARIANTS, 'Variants', true, true, 'grey')
            ->addColumnListChip(static::COL_KEY_CATEGORIES, 'Categories', false, true, 2, 'grey')
            ->addColumnListChip(static::COL_KEY_STORES, 'Stores', false, true, 2, 'grey')
            ->addColumnChip(static::COL_KEY_VISIBILITY, 'Visibility', true, true, 'grey', [
                $this->translatorFacade->trans(static::COLUMN_DATA_VISIBILITY_ONLINE) => 'green',
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
            $this->categoryFilterOptionsProvider->getCategoryFilterOptionsTree()
        )
        ->addFilterSelect('isVisible', 'Visibility', false, [
            '1' => static::COLUMN_DATA_VISIBILITY_ONLINE,
            '0' => static::COLUMN_DATA_VISIBILITY_OFFLINE,
        ])
        ->addFilterSelect('inStores', 'Stores', true, $this->getStoreOptions());

        return $guiTableConfigurationBuilder;
    }

    /**
     * @phpstan-return array<int, string>
     *
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
