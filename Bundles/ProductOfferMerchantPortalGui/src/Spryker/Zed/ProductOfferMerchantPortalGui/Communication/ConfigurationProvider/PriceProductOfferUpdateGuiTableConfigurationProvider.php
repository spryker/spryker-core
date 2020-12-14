<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider;

use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
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
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\UpdateProductOfferController::PARAM_ID_PRODUCT_OFFER
     */
    protected const REQUEST_PARAM_ID_PRODUCT_OFFER = 'product-offer-id';
    protected const REQUEST_PARAM_PRICE_PRODUCT_OFFER_IDS = 'price-product-offer-ids';
    protected const REQUEST_VALUE_PRICE_PRODUCT_OFFER_IDS = '${row.price_product_offer_ids}';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\UpdateProductOfferController::savePricesAction()
     */
    protected const URL_SAVE_PRICES = '/product-offer-merchant-portal-gui/update-product-offer/save-prices?type-price-product-offer-ids=${row.type_price_product_offer_ids}';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\UpdateProductOfferController::deletePricesAction()
     */
    protected const URL_DELETE_PRICE = '/product-offer-merchant-portal-gui/update-product-offer/delete-prices';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Controller\UpdateProductOfferController::priceTableDataAction()
     */
    protected const DATA_URL = '/product-offer-merchant-portal-gui/update-product-offer/price-table-data';

    /**
     * @var int
     */
    protected $idProductOffer;

    /**
     * @phpstan-param array<mixed> $initialData
     *
     * @param int $idProductOffer
     * @param array $initialData
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(int $idProductOffer, array $initialData = []): GuiTableConfigurationTransfer
    {
        $this->idProductOffer = $idProductOffer;
        $guiTableConfigurationBuilder = $this->guiTableFactory->createConfigurationBuilder();

        $guiTableConfigurationBuilder = $this->addColumns($guiTableConfigurationBuilder);
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
            $initialData
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
            static::REQUEST_PARAM_PRICE_PRODUCT_OFFER_IDS => static::REQUEST_VALUE_PRICE_PRODUCT_OFFER_IDS,
        ]);
        $guiTableConfigurationBuilder->addRowActionUrl(
            static::ID_ROW_ACTION_DELETE,
            static::TITLE_ROW_ACTION_DELETE,
            static::URL_DELETE_PRICE . '?' . $deleteUrlParams
        );

        return $guiTableConfigurationBuilder;
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
        $guiTableConfigurationBuilder = parent::setEditableConfiguration($guiTableConfigurationBuilder, $initialData);
        $guiTableConfigurationBuilder->enableInlineDataEditing(static::URL_SAVE_PRICES, static::METHOD_UPDATE_ACTION_URL);

        return $guiTableConfigurationBuilder;
    }
}
