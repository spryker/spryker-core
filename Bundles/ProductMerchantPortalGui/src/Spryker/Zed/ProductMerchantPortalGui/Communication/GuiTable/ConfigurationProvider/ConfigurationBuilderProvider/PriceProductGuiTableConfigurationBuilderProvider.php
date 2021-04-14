<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ConfigurationBuilderProvider;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\CurrencyFilterConfigurationProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\StoreFilterOptionsProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;

class PriceProductGuiTableConfigurationBuilderProvider implements PriceProductGuiTableConfigurationBuilderProviderInterface
{
    protected const TITLE_COLUMN_STORE = 'Store';
    protected const TITLE_COLUMN_CURRENCY = 'Currency';
    protected const TITLE_COLUMN_PREFIX_PRICE_TYPE_NET = 'Net';
    protected const TITLE_COLUMN_PREFIX_PRICE_TYPE_GROSS = 'Gross';

    protected const TITLE_FILTER_IN_STORES = 'Stores';
    protected const TITLE_FILTER_IN_CURRENCIES = 'Currencies';

    protected const FORMAT_STRING_PRICE_KEY = '%s[%s][%s]';

    /**
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected $guiTableFactory;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\StoreFilterOptionsProviderInterface
     */
    protected $storeFilterOptionsProvider;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\CurrencyFilterConfigurationProviderInterface
     */
    protected $currencyFilterConfigurationProvider;

    /**
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\StoreFilterOptionsProviderInterface $storeFilterOptionsProvider
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\CurrencyFilterConfigurationProviderInterface $currencyFilterConfigurationProvider
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
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    public function getPriceProductGuiTableConfigurationBuilder(): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();

        $guiTableConfigurationBuilder = $this->addColumns($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addFilters($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addEditableColumns($guiTableConfigurationBuilder);

        $guiTableConfigurationBuilder
            ->setDefaultPageSize(10)
            ->isSearchEnabled(false)
            ->isColumnConfiguratorEnabled(false);

        return $guiTableConfigurationBuilder;
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
            PriceProductTableViewTransfer::STORE,
            static::TITLE_COLUMN_STORE,
            true,
            false,
            'grey'
        )->addColumnChip(
            PriceProductTableViewTransfer::CURRENCY,
            static::TITLE_COLUMN_CURRENCY,
            true,
            false,
            'blue'
        );

        foreach ($this->priceProductFacade->getPriceTypeValues() as $priceTypeTransfer) {
            $idPriceTypeName = mb_strtolower($priceTypeTransfer->getNameOrFail());
            $titlePriceTypeName = ucfirst($idPriceTypeName);
            $idNetColumn = sprintf(
                static::FORMAT_STRING_PRICE_KEY,
                $idPriceTypeName,
                PriceProductTransfer::MONEY_VALUE,
                MoneyValueTransfer::NET_AMOUNT
            );

            $idGrossColumn = sprintf(
                static::FORMAT_STRING_PRICE_KEY,
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
    protected function addEditableColumns(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addEditableColumnSelect(
            PriceProductTableViewTransfer::STORE,
            static::TITLE_COLUMN_STORE,
            false,
            $this->storeFilterOptionsProvider->getStoreOptions()
        )->addEditableColumnSelect(
            PriceProductTableViewTransfer::CURRENCY,
            static::TITLE_COLUMN_CURRENCY,
            false,
            $this->currencyFilterConfigurationProvider->getCurrencyOptions()
        );

        foreach ($this->priceProductFacade->getPriceTypeValues() as $priceTypeTransfer) {
            $idPriceTypeName = mb_strtolower($priceTypeTransfer->getNameOrFail());
            $titlePriceTypeName = ucfirst($idPriceTypeName);
            $idNetColumn = sprintf(
                static::FORMAT_STRING_PRICE_KEY,
                $idPriceTypeName,
                PriceProductTransfer::MONEY_VALUE,
                MoneyValueTransfer::NET_AMOUNT
            );
            $idGrossColumn = sprintf(
                static::FORMAT_STRING_PRICE_KEY,
                $idPriceTypeName,
                PriceProductTransfer::MONEY_VALUE,
                MoneyValueTransfer::GROSS_AMOUNT
            );
            $fieldOptions = [
                'attrs' => [
                    'step' => '0.01',
                ],
            ];

            $guiTableConfigurationBuilder->addEditableColumnInput(
                $idNetColumn,
                static::TITLE_COLUMN_PREFIX_PRICE_TYPE_NET . ' ' . $titlePriceTypeName,
                'number',
                $fieldOptions
            )->addEditableColumnInput(
                $idGrossColumn,
                static::TITLE_COLUMN_PREFIX_PRICE_TYPE_GROSS . ' ' . $titlePriceTypeName,
                'number',
                $fieldOptions
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
                static::TITLE_FILTER_IN_STORES,
                true,
                $this->storeFilterOptionsProvider->getStoreOptions()
            )
            ->addFilterSelect(
                'inCurrencies',
                static::TITLE_FILTER_IN_CURRENCIES,
                true,
                $this->currencyFilterConfigurationProvider->getCurrencyOptions()
            );

        return $guiTableConfigurationBuilder;
    }
}
