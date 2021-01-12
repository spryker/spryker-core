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
    public const COL_KEY_SKU = 'sku';
    public const COL_KEY_IMAGE = 'image';
    public const COL_KEY_NAME = 'name';
    public const COL_KEY_SUPER_ATTRIBUTES = 'superAttributes';
    public const COL_KEY_STATUS = 'status';
    public const COL_KEY_VALID_FROM = 'validFrom';
    public const COL_KEY_VALID_TO = 'validTo';

    public const COLUMN_DATA_STATUS_ACTIVE = 'Active';
    public const COLUMN_DATA_STATUS_INACTIVE = 'Inactive';

    protected const SEARCH_PLACEHOLDER = 'Search';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\ProductsConcreteController::tableDataAction()
     */
    protected const DATA_URL = '/product-merchant-portal-gui/products-concrete/table-data';

    /**
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected $guiTableFactory;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        GuiTableFactoryInterface $guiTableFactory,
        ProductMerchantPortalGuiToTranslatorFacadeInterface $translatorFacade
    ) {
        $this->guiTableFactory = $guiTableFactory;
        $this->translatorFacade = $translatorFacade;
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

        $guiTableConfigurationBuilder
            ->setDataSourceUrl($this->getDataUrl($idProductAbstract))
            ->setSearchPlaceholder(static::SEARCH_PLACEHOLDER)
            ->setDefaultPageSize(10);

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
            ->addColumnListChip(static::COL_KEY_SUPER_ATTRIBUTES, 'Super Attributes', false, true, 1, 'grey')
            ->addColumnChip(static::COL_KEY_STATUS, 'Status', true, true, 'grey', [
                $this->translatorFacade->trans(static::COLUMN_DATA_STATUS_ACTIVE) => 'green',
            ])
            ->addColumnDate(static::COL_KEY_VALID_FROM, 'Valid From', true, true)
            ->addColumnDate(static::COL_KEY_VALID_TO, 'Valid To', true, true);

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return string
     */
    protected function getDataUrl(int $idProductAbstract): string
    {
        return sprintf(
            '%s?%s=%s',
            static::DATA_URL,
            ProductConcreteTransfer::FK_PRODUCT_ABSTRACT,
            $idProductAbstract
        );
    }
}
