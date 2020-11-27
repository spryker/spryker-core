<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableSearchConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableSettingsConfigurationTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;

class PriceProductOfferUpdateGuiTableConfigurationProvider extends AbstractPriceProductOfferGuiTableConfigurationProvider implements PriceProductOfferUpdateGuiTableConfigurationProviderInterface
{
    protected const ID_FILTER_IN_STORES = 'inStores';
    protected const ID_FILTER_IN_CURRENCIES = 'inCurrencies';
    protected const ID_ROW_ACTION_DELETE = 'delete-price';

    protected const TITLE_FILTER_STORES = 'Stores';
    protected const TITLE_FILTER_CURRENCIES = 'Currencies';
    protected const TITLE_ROW_ACTION_DELETE = 'Delete';
    protected const TITLE_ADD_BUTTON = 'Save';
    protected const TITLE_CANCEL_BUTTON = 'Cancel';

    protected const METHOD_UPDATE_ACTION_URL = 'POST';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\UpdateProductOfferController::savePricesAction()
     */
    protected const URL_SAVE_PRICES = '/product-offer-merchant-portal-gui/update-product-offer/save-prices?product-offer-id=$OFFER_ID&type-price-product-offer-ids=${row.type_price_product_offer_ids}';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\UpdateProductOfferController::deletePricesAction()
     */
    protected const URL_DELETE_PRICE = '/product-offer-merchant-portal-gui/update-product-offer/delete-prices?product-offer-id=$OFFER_ID&price-product-offer-ids=${row.price_product_offer_ids}';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\UpdateProductOfferController::priceTableDataAction()
     */
    protected const DATA_URL = '/product-offer-merchant-portal-gui/update-product-offer/price-table-data?product-offer-id=$OFFER_ID';

    /**
     * @var int
     */
    protected $idProductOffer;

    /**
     * @phpstan-param array<mixed>|null $initialData
     *
     * @param int $idProductOffer
     * @param array|null $initialData
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(int $idProductOffer, ?array $initialData = []): GuiTableConfigurationTransfer
    {
        $this->idProductOffer = $idProductOffer;
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();

        $guiTableConfigurationBuilder = $this->addColumns($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addFilters($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addRowActions($guiTableConfigurationBuilder);

        $guiTableConfigurationBuilder
            ->setDataSourceUrl(str_replace(static::PARAM_ID_PRODUCT_OFFER, (string)$idProductOffer, static::DATA_URL))
            ->setIsItemSelectionEnabled(false)
            ->setDefaultPageSize(25);

        $guiTableConfigurationBuilder = $this->setEditableConfiguration(
            $guiTableConfigurationBuilder,
            $initialData
        );

        $guiTableSearchConfigurationTransfer = (new GuiTableSearchConfigurationTransfer())->setIsEnabled(false);
        $guiTableSettingsConfigurationTransfer = (new GuiTableSettingsConfigurationTransfer())->setEnabled(false);

        return $guiTableConfigurationBuilder->createConfiguration()
            ->setSearch($guiTableSearchConfigurationTransfer)
            ->setSettings($guiTableSettingsConfigurationTransfer);
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addFilters(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addFilterSelect(static::ID_FILTER_IN_STORES, static::TITLE_FILTER_STORES, true, $this->getStoreOptions())
            ->addFilterSelect(static::ID_FILTER_IN_CURRENCIES, static::TITLE_FILTER_CURRENCIES, true, $this->getCurrencyOptions());

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addRowActions(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $guiTableConfigurationBuilder->addRowActionUrl(
            static::ID_ROW_ACTION_DELETE,
            static::TITLE_ROW_ACTION_DELETE,
            str_replace(static::PARAM_ID_PRODUCT_OFFER, (string)$this->idProductOffer, static::URL_DELETE_PRICE)
        );

        return $guiTableConfigurationBuilder;
    }

    /**
     * @param \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder
     *
     * @return \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface
     */
    protected function addEditableButtons(GuiTableConfigurationBuilderInterface $guiTableConfigurationBuilder): GuiTableConfigurationBuilderInterface
    {
        $editableUrl = str_replace(static::PARAM_ID_PRODUCT_OFFER, (string)$this->idProductOffer, static::URL_SAVE_PRICES);

        $guiTableConfigurationBuilder = parent::addEditableButtons($guiTableConfigurationBuilder)
            ->setEditableUpdateActionUrl(static::METHOD_UPDATE_ACTION_URL, $editableUrl)
            ->addEditableUpdateActionAddButton(static::TITLE_ADD_BUTTON)
            ->addEditableUpdateActionCancelButton(static::TITLE_CANCEL_BUTTON);

        return $guiTableConfigurationBuilder;
    }
}
