<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductScheduleDataImport\Business\Model\DataSet;

interface PriceProductScheduleDataSetInterface
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
    public const KEY_INCLUDED_FROM = 'from_included';

    /**
     * @var string
     */
    public const KEY_INCLUDED_TO = 'to_included';

    /**
     * @var string
     */
    public const FK_CURRENCY = 'fk_currency';

    /**
     * @var string
     */
    public const FK_STORE = 'fk_store';

    /**
     * @var string
     */
    public const FK_PRICE_TYPE = 'fk_price_type';

    /**
     * @var string
     */
    public const FK_PRODUCT_CONCRETE = 'fk_product';

    /**
     * @var string
     */
    public const FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

    /**
     * @var string
     */
    public const FK_PRICE_PRODUCT_SCHEDULE_LIST = 'fk_price_product_schedule_list';
}
