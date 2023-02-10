<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface;

class ProductGuiTableConfigurationProvider implements ProductGuiTableConfigurationProviderInterface
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
    public const COL_KEY_STATUS = 'status';

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
    public const COLUMN_DATA_STATUS_ACTIVE = 'Active';

    /**
     * @var string
     */
    public const COLUMN_DATA_STATUS_INACTIVE = 'Inactive';

    /**
     * @var string
     */
    protected const SEARCH_PLACEHOLDER = 'Search';

    /**
     * @var string
     */
    protected const TITLE_ROW_ACTION_UPDATE_PRODUCT = 'Manage Product';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\ProductsConcreteController::tableDataAction()
     *
     * @var string
     */
    protected const DATA_URL = '/product-merchant-portal-gui/products-concrete/table-data';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\ProductsConcreteController::bulkEditAction()
     *
     * @var string
     */
    protected const BULK_EDIT_URL = '/product-merchant-portal-gui/products-concrete/bulk-edit?product-ids=${rowIds}';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\UpdateProductConcreteController::indexAction()
     *
     * @var string
     */
    protected const ROW_EDIT_URL = '/product-merchant-portal-gui/update-product-concrete?product-id=${row.%s}';

    /**
     * @var string
     */
    protected const ROW_EDIT_ID = 'update-product';

    /**
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected GuiTableFactoryInterface $guiTableFactory;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade;

    /**
     * @var array<\Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\ProductConcreteTableExpanderPluginInterface>
     */
    protected array $productConcreteTableExpanderPlugins;

    /**
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     * @param array<\Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\ProductConcreteTableExpanderPluginInterface> $productConcreteTableExpanderPlugins
     */
    public function __construct(
        GuiTableFactoryInterface $guiTableFactory,
        ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade,
        array $productConcreteTableExpanderPlugins = []
    ) {
        $this->guiTableFactory = $guiTableFactory;
        $this->translatorFacade = $translatorFacade;
        $this->productConcreteTableExpanderPlugins = $productConcreteTableExpanderPlugins;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(int $idProductAbstract): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();

        $guiTableConfigurationBuilder = $this->addColumns($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addFilters($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addBatchActions($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addRowActions($guiTableConfigurationBuilder);

        $dataSourceUrl = sprintf(
            '%s?%s=%s',
            static::DATA_URL,
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT,
            $idProductAbstract,
        );

        $guiTableConfigurationBuilder
            ->setDataSourceUrl($dataSourceUrl)
            ->setSearchPlaceholder(static::SEARCH_PLACEHOLDER)
            ->setDefaultPageSize(10)
            ->setIsItemSelectionEnabled(true);

        $guiTableConfigurationTransfer = $guiTableConfigurationBuilder->createConfiguration();

        foreach ($this->productConcreteTableExpanderPlugins as $productConcreteTableExpanderPlugin) {
            $guiTableConfigurationTransfer = $productConcreteTableExpanderPlugin->expandConfiguration($guiTableConfigurationTransfer);
        }

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
            ->addColumnListChip(static::COL_KEY_SUPER_ATTRIBUTES, 'Super Attributes', false, true, 1, 'gray')
            ->addColumnChip(static::COL_KEY_STATUS, 'Status', true, true, 'gray', [
                $this->translatorFacade->trans(static::COLUMN_DATA_STATUS_ACTIVE) => 'green',
            ])
            ->addColumnDate(static::COL_KEY_VALID_FROM, 'Valid From', true, true)
            ->addColumnDate(static::COL_KEY_VALID_TO, 'Valid To', true, true);

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addFilters(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder
            ->addFilterSelect('isActive', 'Status', false, [
                '1' => static::COLUMN_DATA_STATUS_ACTIVE,
                '0' => static::COLUMN_DATA_STATUS_INACTIVE,
            ])
            ->addFilterDateRange('validity', 'Validity');

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addBatchActions(
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
    ): GuiTableConfigurationBuilderInterface {
        $guiTableConfigurationBuilder->addBatchActionDrawerAjaxForm(
            'Edit',
            'Bulk edit',
            static::BULK_EDIT_URL,
        );

        $guiTableConfigurationBuilder->setBatchActionRowIdPath(ProductConcreteTransfer::ID_PRODUCT_CONCRETE);

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
            static::ROW_EDIT_ID,
            static::TITLE_ROW_ACTION_UPDATE_PRODUCT,
            sprintf(
                static::ROW_EDIT_URL,
                ProductConcreteTransfer::ID_PRODUCT_CONCRETE,
            ),
        )->setRowClickAction(static::ROW_EDIT_ID);

        return $guiTableConfigurationBuilder;
    }
}
