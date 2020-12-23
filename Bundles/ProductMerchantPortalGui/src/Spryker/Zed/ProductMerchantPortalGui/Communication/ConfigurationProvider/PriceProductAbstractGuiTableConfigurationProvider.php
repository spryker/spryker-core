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
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductAbstractForm;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;

class PriceProductAbstractGuiTableConfigurationProvider implements PriceProductAbstractGuiTableConfigurationProviderInterface
{
    protected const TITLE_COLUMN_STORE = 'Store';
    protected const TITLE_COLUMN_CURRENCY = 'Currency';
    protected const TITLE_COLUMN_PREFIX_PRICE_TYPE_NET = 'Net';
    protected const TITLE_COLUMN_PREFIX_PRICE_TYPE_GROSS = 'Gross';

    protected const TITLE_FILTER_IN_STORES = 'Stores';
    protected const TITLE_FILTER_IN_CURRENCIES = 'Currencies';

    protected const TITLE_ROW_ACTION_DELETE = 'Delete';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\UpdateProductAbstractController::tableDataAction()
     */
    protected const DATA_URL = '/product-merchant-portal-gui/update-product-abstract/table-data';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\UpdateProductAbstractController::savePricesAction()
     */
    protected const URL_SAVE_PRICES = '/product-merchant-portal-gui/update-product-abstract/save-prices';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\UpdateProductAbstractController::deletePricesAction()
     */
    protected const URL_DELETE_PRICE = '/product-merchant-portal-gui/update-product-abstract/delete-prices';

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
     * @phpstan-param array<mixed> $initialData
     *
     * @param int $idProductAbstract
     * @param array $initialData
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(int $idProductAbstract, array $initialData = []): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();

        $guiTableConfigurationBuilder = $this->addColumns($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addFilters($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addRowActions($guiTableConfigurationBuilder);

        $guiTableConfigurationBuilder
            ->setDataSourceUrl($this->getDataUrl($idProductAbstract))
            ->setDefaultPageSize(10)
            ->isSearchEnabled(false)
            ->isColumnConfiguratorEnabled(false);

        $guiTableConfigurationBuilder = $this->setEditableConfiguration(
            $guiTableConfigurationBuilder,
            $initialData
        );

        return $guiTableConfigurationBuilder->createConfiguration();
    }

    /**
     * @phpstan-param array<mixed> $initialData
     *
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     * @param array $initialData
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function setEditableConfiguration(
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder,
        array $initialData = []
    ): GuiTableConfigurationBuilderInterface {
        $formInputName = sprintf('%s[%s]', ProductAbstractForm::BLOCK_PREFIX, ProductAbstractTransfer::PRICES);

        $guiTableConfigurationBuilder->enableAddingNewRows($formInputName, $initialData);
        $guiTableConfigurationBuilder = $this->addEditableColumns($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder->enableInlineDataEditing($this->getSavePricesUrl(), 'POST');

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
    protected function addEditableColumns(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addEditableColumnSelect(
            PriceProductAbstractTableViewTransfer::STORE,
            static::TITLE_COLUMN_STORE,
            false,
            $this->storeFilterOptionsProvider->getStoreOptions()
        )->addEditableColumnSelect(
            PriceProductAbstractTableViewTransfer::CURRENCY,
            static::TITLE_COLUMN_CURRENCY,
            false,
            $this->currencyFilterConfigurationProvider->getCurrencyOptions()
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

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addRowActions(
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
    ): GuiTableConfigurationBuilderInterface {
        $guiTableConfigurationBuilder->addRowActionUrl('delete-price', static::TITLE_ROW_ACTION_DELETE, $this->getDeletePricesUrl());

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
            PriceProductAbstractTableViewTransfer::ID_PRODUCT_ABSTRACT,
            $idProductAbstract
        );
    }

    /**
     * @return string
     */
    protected function getSavePricesUrl(): string
    {
        return sprintf(
            '%s?%s=${row.%s}&%s=${row.%s}',
            static::URL_SAVE_PRICES,
            PriceProductAbstractTableViewTransfer::TYPE_PRICE_PRODUCT_STORE_IDS,
            PriceProductAbstractTableViewTransfer::TYPE_PRICE_PRODUCT_STORE_IDS,
            PriceProductAbstractTableViewTransfer::ID_PRODUCT_ABSTRACT,
            PriceProductAbstractTableViewTransfer::ID_PRODUCT_ABSTRACT
        );
    }

    /**
     * @return string
     */
    protected function getDeletePricesUrl(): string
    {
        return sprintf(
            '%s?%s=${row.%s}&%s=${row.%s}',
            static::URL_DELETE_PRICE,
            PriceProductAbstractTableViewTransfer::ID_PRODUCT_ABSTRACT,
            PriceProductAbstractTableViewTransfer::ID_PRODUCT_ABSTRACT,
            PriceProductAbstractTableViewTransfer::PRICE_PRODUCT_DEFAULT_IDS,
            PriceProductAbstractTableViewTransfer::PRICE_PRODUCT_DEFAULT_IDS
        );
    }
}
