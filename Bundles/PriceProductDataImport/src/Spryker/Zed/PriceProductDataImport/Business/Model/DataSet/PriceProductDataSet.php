<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductDataImport\Business\Model\DataSet;

interface PriceProductDataSet
{
    /**
     * @var string
     */
    public const KEY_ABSTRACT_SKU = 'abstract_sku';

    /**
     * @var string
     */
    public const KEY_CONCRETE_SKU = 'concrete_sku';

    /**
     * @var string
     */
    public const KEY_STORE = 'store';

    /**
     * @var string
     */
    public const KEY_CURRENCY = 'currency';

    /**
     * @var string
     */
    public const KEY_PRICE_TYPE = 'price_type';

    /**
     * @var string
     */
    public const KEY_PRICE_NET = 'value_net';

    /**
     * @var string
     */
    public const KEY_PRICE_GROSS = 'value_gross';

    /**
     * @var string
     */
    public const KEY_PRICE_DATA_PREFIX = 'price_data.';

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
    public const ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var string
     */
    public const ID_PRODUCT_CONCRETE = 'id_product_concrete';

    /**
     * @var string
     */
    public const ID_STORE = 'id_store';

    /**
     * @var string
     */
    public const ID_CURRENCY = 'id_currency';
}
