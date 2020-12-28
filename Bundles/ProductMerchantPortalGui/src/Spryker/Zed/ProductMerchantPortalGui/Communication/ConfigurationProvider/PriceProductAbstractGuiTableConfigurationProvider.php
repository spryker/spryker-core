<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductAbstractTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;

class PriceProductAbstractGuiTableConfigurationProvider implements PriceProductAbstractGuiTableConfigurationProviderInterface
{
    protected const TITLE_COLUMN_STORE = 'Store';
    protected const TITLE_COLUMN_CURRENCY = 'Currency';
    protected const TITLE_COLUMN_PREFIX_PRICE_TYPE_NET = 'Net';
    protected const TITLE_COLUMN_PREFIX_PRICE_TYPE_GROSS = 'Gross';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\UpdateProductAbstractController::tableDataAction()
     */
    protected const DATA_URL = '/product-merchant-portal-gui/update-product-abstract/table-data';

    /**
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected $guiTableFactory;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\StoreFilterOptionsProviderInterface
     */
    protected $storeFilterOptionsProvider;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\CurrencyFilterConfigurationProviderInterface
     */
    protected $currencyFilterConfigurationProvider;

    /**
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\StoreFilterOptionsProviderInterface $storeFilterOptionsProvider
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\ConfigurationProvider\CurrencyFilterConfigurationProviderInterface $currencyFilterConfigurationProvider
     */
    public function __construct(
        GuiTableFactoryInterface $guiTableFactory,
        ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        StoreFilterOptionsProviderInterface $storeFilterOptionsProvider,
        CurrencyFilterConfigurationProviderInterface $currencyFilterConfigurationProvider
    ) {
        $this->guiTableFactory = $guiTableFactory;
        $this->priceProductFacade = $priceProductFacade;
        $this->storeFilterOptionsProvider = $storeFilterOptionsProvider;
        $this->currencyFilterConfigurationProvider = $currencyFilterConfigurationProvider;
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
            ->setDefaultPageSize(10);
//            ->isSearchEnabled(false)
//            ->isColumnConfiguratorEnabled(false)

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
            static::TITLE_COLUMN_STORE,
            true,
            false,
            'grey'
        )->addColumnChip(
            PriceProductAbstractTableViewTransfer::CURRENCY,
            static::TITLE_COLUMN_CURRENCY,
            true,
            false,
            'blue'
        );

        foreach ($this->priceProductFacade->getPriceTypeValues() as $priceTypeTransfer) {
            $idPriceTypeName = mb_strtolower($priceTypeTransfer->getName());
            $titlePriceTypeName = ucfirst($idPriceTypeName);
            $idNetColumn = sprintf(
                '%s[%s][%s]',
                $idPriceTypeName,
                PriceProductTransfer::MONEY_VALUE,
                MoneyValueTransfer::NET_AMOUNT
            );

            $idGrossColumn = sprintf(
                '%s[%s][%s]',
                $idPriceTypeName,
                PriceProductTransfer::MONEY_VALUE,
                MoneyValueTransfer::GROSS_AMOUNT
            );

            $guiTableConfigurationBuilder->addColumnText(
                $idNetColumn,
                static::TITLE_COLUMN_PREFIX_PRICE_TYPE_NET . ' ' . $titlePriceTypeName,
                true,
                false
            )->addColumnText(
                $idGrossColumn,
                static::TITLE_COLUMN_PREFIX_PRICE_TYPE_GROSS . ' ' . $titlePriceTypeName,
                true,
                false
            );
        }

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
            ->addFilterSelect(
                'inStores',
                'Stores',
                true,
                $this->storeFilterOptionsProvider->getStoreOptions()
            )
            ->addFilterSelect(
                'inCurrencies',
                'Currencies',
                true,
                $this->currencyFilterConfigurationProvider->getCurrencyOptions()
            );

        return $guiTableConfigurationBuilder;
    }
}
