<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Shared\GuiTable\GuiTableFactoryInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToStoreFacadeInterface;

class ProductOfferPriceGuiTableConfigurationProvider implements ProductOfferPriceGuiTableConfigurationProviderInterface
{
    public const COL_STORE = 'store';
    public const COL_CURRENCY = 'currency';

    protected const PARAM_ID_PRODUCT_OFFER = '$OFFER_ID';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\UpdateProductOfferController::priceTableDataAction()
     */
    protected const DATA_URL = '/product-offer-merchant-portal-gui/update-product-offer/price-table-data?product-offer-id=$OFFER_ID';

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
     * @param int $idProductOffer
     * @param array $initialData
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(int $idProductOffer, ?array $initialData = []): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();

        $guiTableConfigurationBuilder = $this->addColumns($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addFilters($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addRowActions($guiTableConfigurationBuilder);

        $guiTableConfigurationBuilder
            ->setTableTitle('List of Prices')
            ->setDataSourceUrl(str_replace(static::PARAM_ID_PRODUCT_OFFER, $idProductOffer, static::DATA_URL))
            ->setIsItemSelectionEnabled(false)
            ->setDefaultPageSize(25)
            ->setSearchPlaceholder('');

        $guiTableConfigurationBuilder = $this->setEditableConfiguration($guiTableConfigurationBuilder, $initialData);

        return $guiTableConfigurationBuilder->createConfiguration();
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addColumns(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addColumnChip(static::COL_STORE, 'Store', true, false, '', [])
            ->addColumnChip(static::COL_CURRENCY, 'Currency', true, false, 'blue', []);

        foreach ($this->priceProductFacade->getPriceTypeValues() as $priceTypeTransfer) {
            $guiTableConfigurationBuilder->addColumnText(
                mb_strtolower($priceTypeTransfer->getName()) . '_net',
                'Net ' . ucfirst(mb_strtolower($priceTypeTransfer->getName())),
                true,
                false
            )->addColumnText(
                mb_strtolower($priceTypeTransfer->getName()) . '_gross',
                'Gross ' . ucfirst(mb_strtolower($priceTypeTransfer->getName())),
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
        $guiTableConfigurationBuilder->addFilterSelect('inStores', 'Stores', true, $this->getStoreOptions())
            ->addFilterSelect('inCurrencies', 'Currencies', true, $this->getCurrencyOptions());

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
            $storeOptions[$storeTransfer->getIdStore()] = $storeTransfer->getName();
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
                $currencyOptions[$currencyTransfer->getIdCurrency()] = $currencyTransfer->getCode();
            }
        }

        return $currencyOptions;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addRowActions(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addRowActionOpenFormOverlay(
            'delete-price',
            'Delete price',
            '/product-offer-merchant-portal-gui/update-product-offer/delete-prices?product-offer-id=$OFFER_ID&${row.price_product_offer_ids}'
        )->setRowClickAction('delete-price');

        return $guiTableConfigurationBuilder;
    }

    protected function setEditableConfiguration(
        GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder,
        ?array $initialData = []
    ): GuiTableConfigurationBuilderInterface {
        $guiTableConfigurationBuilder->setTableEditable(true)
            ->setEditableCreateActionFormInputName('test');

        if ($initialData) {
            $guiTableConfigurationBuilder->setEditableCreateActionInitialData($initialData);
        }

        $guiTableConfigurationBuilder = $this->addEditableButtons($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addEditableColumns($guiTableConfigurationBuilder);

        return $guiTableConfigurationBuilder;
    }

    protected function addEditableButtons(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addEditableCreateActionAddButton('Create')
            ->addEditableCreateActionCancelButton('Cancel')
            ->setEditableUpdateActionUrl('POST', 'test')
            ->addEditableUpdateActionAddButton('Save')
            ->addEditableUpdateActionCancelButton('Cancel');

        return $guiTableConfigurationBuilder;
    }

    protected function addEditableColumns(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addEditableColumnSelect(static::COL_STORE, 'Store', $this->getStoreOptions())
            ->addEditableColumnSelect(static::COL_CURRENCY, 'Currency', $this->getCurrencyOptions());

        foreach ($this->priceProductFacade->getPriceTypeValues() as $priceTypeTransfer) {
            $guiTableConfigurationBuilder->addEditableColumnInput(
                mb_strtolower($priceTypeTransfer->getName()) . '_net',
                'Net ' . ucfirst(mb_strtolower($priceTypeTransfer->getName()))
            )->addEditableColumnInput(
                mb_strtolower($priceTypeTransfer->getName()) . '_gross',
                'Gross ' . ucfirst(mb_strtolower($priceTypeTransfer->getName()))
            );
        }

        return $guiTableConfigurationBuilder;
    }
}
