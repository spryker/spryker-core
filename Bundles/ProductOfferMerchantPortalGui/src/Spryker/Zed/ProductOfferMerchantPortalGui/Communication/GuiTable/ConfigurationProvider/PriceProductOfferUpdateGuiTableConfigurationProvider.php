<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\GuiTable\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;

class PriceProductOfferUpdateGuiTableConfigurationProvider extends AbstractPriceProductOfferGuiTableConfigurationProvider implements PriceProductOfferUpdateGuiTableConfigurationProviderInterface
{
    /**
     * @var string
     */
    protected const ID_FILTER_IN_STORES = 'inStores';

    /**
     * @var string
     */
    protected const ID_FILTER_IN_CURRENCIES = 'inCurrencies';

    /**
     * @var string
     */
    protected const ID_ROW_ACTION_DELETE = 'delete-price';

    /**
     * @var string
     */
    protected const TITLE_FILTER_STORES = 'Stores';

    /**
     * @var string
     */
    protected const TITLE_FILTER_CURRENCIES = 'Currencies';

    /**
     * @var string
     */
    protected const TITLE_ROW_ACTION_DELETE = 'Delete';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\UpdateProductOfferController::PARAM_ID_PRODUCT_OFFER
     *
     * @var string
     */
    protected const REQUEST_PARAM_ID_PRODUCT_OFFER = 'product-offer-id';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\DeletePriceProductOfferController::PARAM_QUANTITY
     *
     * @var string
     */
    protected const REQUEST_PARAM_QUANTITY = 'quantity';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\DeletePriceProductOfferController::PARAM_PRICE_PRODUCT_OFFER_IDS
     *
     * @var string
     */
    protected const REQUEST_PARAM_PRICE_PRODUCT_OFFER_IDS = 'price-product-offer-ids';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\SavePriceProductOfferController::indexAction()
     *
     * @var string
     */
    protected const URL_SAVE_PRICES = '/product-offer-merchant-portal-gui/save-price-product-offer?type-price-product-offer-ids=${row.type_price_product_offer_ids}&volume_quantity=${row.volume_quantity}&product_offer_id=%d';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\DeletePriceProductOfferController::indexAction()
     *
     * @var string
     */
    protected const URL_DELETE_PRICE = '/product-offer-merchant-portal-gui/delete-price-product-offer';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\UpdateProductOfferController::priceTableDataAction()
     *
     * @var string
     */
    protected const DATA_URL = '/product-offer-merchant-portal-gui/update-product-offer/price-table-data';

    /**
     * @var int
     */
    protected $idProductOffer;

    /**
     * @param int $idProductOffer
     * @param array<mixed> $initialData
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(int $idProductOffer, array $initialData = []): GuiTableConfigurationTransfer
    {
        $priceTypeTransfers = $this->priceProductFacade->getPriceTypeValues();
        $this->idProductOffer = $idProductOffer;
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();

        $guiTableConfigurationBuilder = $this->addColumns($guiTableConfigurationBuilder, $priceTypeTransfers);
        $guiTableConfigurationBuilder = $this->addFilters($guiTableConfigurationBuilder);
        $guiTableConfigurationBuilder = $this->addRowActions($guiTableConfigurationBuilder);

        $dataSourceUrl = static::DATA_URL . sprintf('?%s=%d', static::REQUEST_PARAM_ID_PRODUCT_OFFER, $idProductOffer);
        $guiTableConfigurationBuilder
            ->setDataSourceUrl($dataSourceUrl)
            ->setIsItemSelectionEnabled(false)
            ->setDefaultPageSize(25)
            ->isSearchEnabled(false)
            ->isColumnConfiguratorEnabled(false);

        $guiTableConfigurationBuilder = $this->setEditableConfiguration(
            $guiTableConfigurationBuilder,
            $priceTypeTransfers,
            $initialData,
        );

        return $guiTableConfigurationBuilder->createConfiguration();
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
        $deleteUrlParams = http_build_query([
            static::REQUEST_PARAM_ID_PRODUCT_OFFER => (string)$this->idProductOffer,
        ]);

        $url = sprintf(
            '%s?%s&%s=${row.price_product_offer_ids}&%s=${row.volume_quantity}',
            static::URL_DELETE_PRICE,
            $deleteUrlParams,
            static::REQUEST_PARAM_PRICE_PRODUCT_OFFER_IDS,
            static::REQUEST_PARAM_QUANTITY,
        );

        $guiTableConfigurationBuilder->addRowActionHttp(
            static::ID_ROW_ACTION_DELETE,
            static::TITLE_ROW_ACTION_DELETE,
            $url,
        );

        return $guiTableConfigurationBuilder;
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
        $guiTableConfigurationBuilder = parent::setEditableConfiguration(
            $guiTableConfigurationBuilder,
            $priceTypeTransfers,
            $initialData,
        );

        $urlSavePrices = sprintf(static::URL_SAVE_PRICES, $this->idProductOffer);

        $guiTableConfigurationBuilder->enableInlineDataEditing($urlSavePrices);

        return $guiTableConfigurationBuilder;
    }
}
