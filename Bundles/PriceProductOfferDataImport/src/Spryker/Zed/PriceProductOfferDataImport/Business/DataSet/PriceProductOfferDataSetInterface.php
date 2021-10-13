<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business\DataSet;

interface PriceProductOfferDataSetInterface
{
    /**
     * @var string
     */
    public const PRODUCT_OFFER_REFERENCE = 'product_offer_reference';
    /**
     * @var string
     */
    public const PRICE_TYPE = 'price_type';
    /**
     * @var string
     */
    public const STORE = 'store';
    /**
     * @var string
     */
    public const CURRENCY = 'currency';
    /**
     * @var string
     */
    public const VALUE_NET = 'value_net';
    /**
     * @var string
     */
    public const VALUE_GROSS = 'value_gross';
    /**
     * @var string
     */
    public const PRICE_DATA_VOLUME_PRICES = 'price_data.volume_prices';
    /**
     * @var string
     */
    public const VOLUME_PRICES = 'volume_prices';

    /**
     * @var string
     */
    public const KEY_PRICE_DATA = 'price_data';
    /**
     * @var string
     */
    public const KEY_PRICE_DATA_CHECKSUM = 'price_data_checksum';

    /**
     * @var string
     */
    public const CONCRETE_SKU = 'concrete_sku';
    /**
     * @var string
     */
    public const ID_PRODUCT_CONCRETE = 'id_product_concrete';
    /**
     * @var string
     */
    public const FK_PRICE_PRODUCT_STORE = 'fk_price_product_store';
    /**
     * @var string
     */
    public const FK_PRICE_PRODUCT = 'fk_price_product';
    /**
     * @var string
     */
    public const FK_PRODUCT_OFFER = 'fk_product_offer';
    /**
     * @var string
     */
    public const FK_PRICE_TYPE = 'fk_price_type';
    /**
     * @var string
     */
    public const FK_STORE = 'fk_store';
    /**
     * @var string
     */
    public const FK_CURRENCY = 'fk_currency';
}
