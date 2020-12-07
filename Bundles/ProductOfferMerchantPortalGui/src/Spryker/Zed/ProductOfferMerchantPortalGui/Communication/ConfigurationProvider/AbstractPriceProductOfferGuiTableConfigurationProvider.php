<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductOfferTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface;

abstract class AbstractPriceProductOfferGuiTableConfigurationProvider
{
    protected const PARAM_ID_PRODUCT_OFFER = '$OFFER_ID';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\ProductOfferForm::FIELD_PRODUCT_OFFER_PRICES
     */
    protected const FIELD_PRODUCT_OFFER_PRICES = 'prices';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\ProductOfferForm::BLOCK_PREFIX
     */
    protected const BLOCK_PREFIX = 'productOffer';

    protected const TITLE_COLUMN_STORE = 'Store';
    protected const TITLE_COLUMN_CURRENCY = 'Currency';
    protected const TITLE_COLUMN_PREFIX_PRICE_TYPE_NET = 'Net';
    protected const TITLE_COLUMN_PREFIX_PRICE_TYPE_GROSS = 'Gross';

    /**
     * @var \Spryker\Shared\GuiTable\GuiTableFactoryInterface
     */
    protected $guiTableFactory;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        GuiTableFactoryInterface $guiTableFactory,
        ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductOfferMerchantPortalGuiToStoreFacadeInterface $storeFacade,
        ProductOfferMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade
    ) {
        $this->guiTableFactory = $guiTableFactory;
        $this->priceProductFacade = $priceProductFacade;
        $this->storeFacade = $storeFacade;
        $this->currencyFacade = $currencyFacade;
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
        $formInputName = sprintf('%s[%s]', static::BLOCK_PREFIX, static::FIELD_PRODUCT_OFFER_PRICES);

        $guiTableConfigurationBuilder->enableAddingNewRows($formInputName, $initialData);
        $guiTableConfigurationBuilder = $this->addEditableColumns($guiTableConfigurationBuilder);

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
            PriceProductOfferTableViewTransfer::STORE,
            static::TITLE_COLUMN_STORE,
            false,
            $this->getStoreOptions()
        )->addEditableColumnSelect(
            PriceProductOfferTableViewTransfer::CURRENCY,
            static::TITLE_COLUMN_CURRENCY,
            false,
            $this->getCurrencyOptions()
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
                'type' => 'number',
                'attrs' => [
                    'step' => '0.01',
                ],
            ];

            $guiTableConfigurationBuilder->addEditableColumnInput(
                $idNetColumn,
                static::TITLE_COLUMN_PREFIX_PRICE_TYPE_NET . ' ' . $titlePriceTypeName,
                $fieldOptions
            )->addEditableColumnInput(
                $idGrossColumn,
                static::TITLE_COLUMN_PREFIX_PRICE_TYPE_GROSS . ' ' . $titlePriceTypeName,
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
    protected function addColumns(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addColumnChip(
            PriceProductOfferTableViewTransfer::STORE,
            static::TITLE_COLUMN_STORE,
            true,
            false,
            'grey',
            []
        )->addColumnChip(
            PriceProductOfferTableViewTransfer::CURRENCY,
            static::TITLE_COLUMN_CURRENCY,
            true,
            false,
            'blue',
            []
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
     * @return string[]
     */
    protected function getStoreOptions(): array
    {
        $storeTransfers = $this->storeFacade->getAllStores();

        $storeOptions = [];
        foreach ($storeTransfers as $storeTransfer) {
            $storeOptions[(string)$storeTransfer->getIdStore()] = $storeTransfer->getName();
        }

        return $storeOptions;
    }

    /**
     * @return string[]
     */
    protected function getCurrencyOptions(): array
    {
        $storeWithCurrencyTransfers = $this->currencyFacade->getAllStoresWithCurrencies();

        $currencyOptions = [];
        foreach ($storeWithCurrencyTransfers as $storeWithCurrencyTransfer) {
            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                $currencyOptions[(string)$currencyTransfer->getIdCurrency()] = $currencyTransfer->getCode();
            }
        }

        return $currencyOptions;
    }
}
