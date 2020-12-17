<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\PriceProductAbstractTableViewTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;

class PriceProductAbstractGuiTableConfigurationProvider implements PriceProductAbstractGuiTableConfigurationProviderInterface
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\UpdateProductAbstractController::tableDataAction()
     */
    protected const DATA_URL = '/product-merchant-portal-gui/update-product-abstract/table-data';

    /**
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected $guiTableFactory;

    /**
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     */
    public function __construct(GuiTableFactoryInterface $guiTableFactory)
    {
        $this->guiTableFactory = $guiTableFactory;
    }

    /**
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();

        $guiTableConfigurationBuilder = $this->addColumns($guiTableConfigurationBuilder);

        $guiTableConfigurationBuilder
            ->setDataSourceUrl(static::DATA_URL)
            ->setDefaultPageSize(10);

        return $guiTableConfigurationBuilder->createConfiguration();
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addColumns(
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
    ): GuiTableConfigurationBuilderInterface {
        $guiTableConfigurationBuilder->addColumnChip(
            PriceProductAbstractTableViewTransfer::STORE,
            'Store',
            true,
            false,
            'grey'
        )->addColumnChip(
            PriceProductAbstractTableViewTransfer::CURRENCY,
            'Currency',
            true,
            false,
            'blue'
        );

        return $guiTableConfigurationBuilder;
    }
}
