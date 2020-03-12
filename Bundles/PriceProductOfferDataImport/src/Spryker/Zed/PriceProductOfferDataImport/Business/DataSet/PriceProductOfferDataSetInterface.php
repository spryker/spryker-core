<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business\DataSet;

interface PriceProductOfferDataSetInterface
{
    public const PRODUCT_OFFER_REFERENCE = 'product_offer_reference';
    public const PRICE_TYPE = 'price_type';
    public const STORE = 'store';
    public const CURRENCY = 'currency';
    public const VALUE_NET = 'value_net';
    public const VALUE_GROSS = 'value_gross';
    public const PRICE_DATA_VOLUME_PRICES = 'price_data.volume_prices';
    public const VOLUME_PRICES = 'volume_prices';

    public const KEY_PRICE_DATA = 'price_data';
    public const KEY_PRICE_DATA_CHECKSUM = 'price_data_checksum';

    public const CONCRETE_SKU = 'concrete_sku';
    public const ID_PRODUCT_CONCRETE = 'id_product_concrete';
    public const FK_PRICE_PRODUCT_STORE = 'fk_price_product_store';
    public const FK_PRICE_PRODUCT = 'fk_price_product';
    public const FK_PRODUCT_OFFER = 'fk_product_offer';
    public const FK_PRICE_TYPE = 'fk_price_type';
    public const FK_STORE = 'fk_store';
    public const FK_CURRENCY = 'fk_currency';
}
