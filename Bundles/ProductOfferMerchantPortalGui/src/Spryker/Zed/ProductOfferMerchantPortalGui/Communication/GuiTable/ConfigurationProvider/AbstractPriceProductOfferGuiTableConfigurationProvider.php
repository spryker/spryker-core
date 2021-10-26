<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableEditableButtonTransfer;
use Generated\Shared\Transfer\PriceProductOfferTableViewTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column\ColumnIdCreatorInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface;

abstract class AbstractPriceProductOfferGuiTableConfigurationProvider
{
    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\ProductOfferForm::FIELD_PRODUCT_OFFER_PRICES
     *
     * @var string
     */
    protected const FIELD_PRODUCT_OFFER_PRICES = 'prices';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\ProductOfferForm::BLOCK_PREFIX
     *
     * @var string
     */
    protected const BLOCK_PREFIX = 'productOffer';

    /**
     * @var string
     */
    protected const TITLE_COLUMN_STORE = 'Store';

    /**
     * @var string
     */
    protected const TITLE_COLUMN_CURRENCY = 'Currency';

    /**
     * @var string
     */
    protected const TITLE_COLUMN_PREFIX_PRICE_TYPE_NET = 'Net';

    /**
     * @var string
     */
    protected const TITLE_COLUMN_PREFIX_PRICE_TYPE_GROSS = 'Gross';

    /**
     * @var string
     */
    protected const TITLE_COLUMN_VOLUME_QUANTITY = 'Volume Quantity';

    /**
     * @var string
     */
    protected const TITLE_EDITABLE_BUTTON = 'Add';

    /**
     * @var string
     */
    protected const INPUT_TYPE_NUMBER = 'number';

    /**
     * @var string
     */
    protected const TYPE_OPTION_VALUE = 'value';

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
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column\ColumnIdCreatorInterface
     */
    protected $columnIdCreator;

    /**
     * @param \Spryker\Shared\GuiTable\GuiTableFactoryInterface $guiTableFactory
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\Column\ColumnIdCreatorInterface $columnIdCreator
     */
    public function __construct(
        GuiTableFactoryInterface $guiTableFactory,
        ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductOfferMerchantPortalGuiToStoreFacadeInterface $storeFacade,
        ProductOfferMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade,
        ColumnIdCreatorInterface $columnIdCreator
    ) {
        $this->guiTableFactory = $guiTableFactory;
        $this->priceProductFacade = $priceProductFacade;
        $this->storeFacade = $storeFacade;
        $this->currencyFacade = $currencyFacade;
        $this->columnIdCreator = $columnIdCreator;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     * @param array<\Generated\Shared\Transfer\PriceTypeTransfer> $priceTypeTransfers
     * @param array<mixed> $initialData
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function setEditableConfiguration(
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder,
        array $priceTypeTransfers,
        array $initialData = []
    ): GuiTableConfigurationBuilderInterface {
        $formInputName = sprintf('%s[%s]', static::BLOCK_PREFIX, static::FIELD_PRODUCT_OFFER_PRICES);

        $guiTableConfigurationBuilder->enableAddingNewRows($formInputName, $initialData, [
            GuiTableEditableButtonTransfer::TITLE => static::TITLE_EDITABLE_BUTTON,
        ]);
        $guiTableConfigurationBuilder = $this->addEditableColumns($guiTableConfigurationBuilder, $priceTypeTransfers);

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     * @param array<\Generated\Shared\Transfer\PriceTypeTransfer> $priceTypeTransfers
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addEditableColumns(
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder,
        array $priceTypeTransfers
    ): GuiTableConfigurationBuilderInterface {
        $guiTableConfigurationBuilder->addEditableColumnSelect(
            $this->columnIdCreator->createStoreColumnId(),
            static::TITLE_COLUMN_STORE,
            false,
            $this->getStoreOptions(),
        )->addEditableColumnSelect(
            $this->columnIdCreator->createCurrencyColumnId(),
            static::TITLE_COLUMN_CURRENCY,
            false,
            $this->getCurrencyOptions(),
        );

        foreach ($priceTypeTransfers as $priceTypeTransfer) {
            $priceTypeName = $this->getPriceTypeName($priceTypeTransfer);
            $titlePriceTypeName = ucfirst(mb_strtolower($priceTypeName));

            $fieldOptions = [
                'attrs' => [
                    'step' => '0.01',
                ],
            ];

            $guiTableConfigurationBuilder->addEditableColumnInput(
                $this->columnIdCreator->createNetAmountColumnId($priceTypeName),
                static::TITLE_COLUMN_PREFIX_PRICE_TYPE_NET . ' ' . $titlePriceTypeName,
                static::INPUT_TYPE_NUMBER,
                $fieldOptions,
            )->addEditableColumnInput(
                $this->columnIdCreator->createGrossAmountColumnId($priceTypeName),
                static::TITLE_COLUMN_PREFIX_PRICE_TYPE_GROSS . ' ' . $titlePriceTypeName,
                static::INPUT_TYPE_NUMBER,
                $fieldOptions,
            );
        }

        $guiTableConfigurationBuilder->addEditableColumnInput(
            $this->columnIdCreator->createVolumeQuantityColumnId(),
            static::TITLE_COLUMN_VOLUME_QUANTITY,
            static::INPUT_TYPE_NUMBER,
            $this->getVolumeQuantityColumnOptions(),
        );

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     * @param array<\Generated\Shared\Transfer\PriceTypeTransfer> $priceTypeTransfers
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addColumns(
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder,
        array $priceTypeTransfers
    ): GuiTableConfigurationBuilderInterface {
        $guiTableConfigurationBuilder->addColumnChip(
            PriceProductOfferTableViewTransfer::STORE,
            static::TITLE_COLUMN_STORE,
            true,
            false,
            'gray',
            [],
        )->addColumnChip(
            PriceProductOfferTableViewTransfer::CURRENCY,
            static::TITLE_COLUMN_CURRENCY,
            true,
            false,
            'blue',
            [],
        );

        foreach ($priceTypeTransfers as $priceTypeTransfer) {
            $priceTypeName = $this->getPriceTypeName($priceTypeTransfer);
            $titlePriceTypeName = ucfirst(mb_strtolower($priceTypeName));

            $guiTableConfigurationBuilder->addColumnText(
                $this->columnIdCreator->createNetAmountColumnId($priceTypeName),
                static::TITLE_COLUMN_PREFIX_PRICE_TYPE_NET . ' ' . $titlePriceTypeName,
                true,
                false,
            )->addColumnText(
                $this->columnIdCreator->createGrossAmountColumnId($priceTypeName),
                static::TITLE_COLUMN_PREFIX_PRICE_TYPE_GROSS . ' ' . $titlePriceTypeName,
                true,
                false,
            );
        }

        $guiTableConfigurationBuilder->addColumnText(
            $this->columnIdCreator->createVolumeQuantityColumnId(),
            static::TITLE_COLUMN_VOLUME_QUANTITY,
            true,
            false,
        );

        return $guiTableConfigurationBuilder;
    }

    /**
     * @return array<string>
     */
    protected function getStoreOptions(): array
    {
        $storeTransfers = $this->storeFacade->getAllStores();

        $storeOptions = [];
        foreach ($storeTransfers as $storeTransfer) {
            $storeOptions[(string)$storeTransfer->getIdStore()] = (string)$storeTransfer->getName();
        }

        return $storeOptions;
    }

    /**
     * @return array<string>
     */
    protected function getCurrencyOptions(): array
    {
        $storeWithCurrencyTransfers = $this->currencyFacade->getAllStoresWithCurrencies();

        $currencyOptions = [];
        foreach ($storeWithCurrencyTransfers as $storeWithCurrencyTransfer) {
            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                $currencyOptions[(string)$currencyTransfer->getIdCurrency()] = (string)$currencyTransfer->getCode();
            }
        }

        return $currencyOptions;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceTypeTransfer $priceTypeTransfer
     *
     * @return string
     */
    protected function getPriceTypeName(PriceTypeTransfer $priceTypeTransfer): string
    {
        return (string)$priceTypeTransfer->getName();
    }

    /**
     * @return array<mixed>
     */
    protected function getVolumeQuantityColumnOptions(): array
    {
        return [
            static::TYPE_OPTION_VALUE => 1,
        ];
    }
}
