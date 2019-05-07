<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleDataImport\Business\Model\DataSet;

interface PriceProductScheduleDataSetInterface
{
    public const KEY_ABSTRACT_SKU = 'abstract_sku';
    public const KEY_CONCRETE_SKU = 'concrete_sku';
    public const KEY_STORE = 'store';
    public const KEY_CURRENCY = 'currency';
    public const KEY_PRICE_TYPE = 'price_type';

    public const KEY_PRICE_NET = 'value_net';
    public const KEY_PRICE_GROSS = 'value_gross';

    public const KEY_INCLUDED_FROM = 'from_included';
    public const KEY_INCLUDED_TO = 'to_included';

    public const FK_CURRENCY = 'fk_currency';
    public const FK_STORE = 'fk_store';
    public const FK_PRICE_TYPE = 'fk_price_type';
    public const FK_PRODUCT_CONCRETE = 'fk_product';
    public const FK_PRODUCT_ABSTRACT = 'fk_product_abstract';
    public const FK_PRICE_PRODUCT_SCHEDULE_LIST = 'fk_price_product_schedule_list';
}
