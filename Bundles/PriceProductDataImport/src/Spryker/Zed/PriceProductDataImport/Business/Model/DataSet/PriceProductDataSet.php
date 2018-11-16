<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductDataImport\Business\Model\DataSet;

interface PriceProductDataSet
{
    public const KEY_ABSTRACT_SKU = 'abstract_sku';
    public const KEY_CONCRETE_SKU = 'concrete_sku';
    public const KEY_STORE = 'store';
    public const KEY_CURRENCY = 'currency';
    public const KEY_PRICE_TYPE = 'price_type';

    public const KEY_PRICE_NET = 'value_net';
    public const KEY_PRICE_GROSS = 'value_gross';

    public const KEY_PRICE_DATA_PREFIX = 'price_data.';
    public const KEY_PRICE_DATA = 'price_data';
    public const KEY_PRICE_DATA_CHECKSUM = 'price_data_checksum';

    public const ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    public const ID_PRODUCT_CONCRETE = 'id_product_concrete';
    public const ID_STORE = 'id_store';
    public const ID_CURRENCY = 'id_currency';
}
