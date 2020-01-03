<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class PriceProductScheduleGuiConfig extends AbstractBundleConfig
{
    /**
     * @see \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig::KEY_ABSTRACT_SKU
     */
    protected const KEY_ABSTRACT_SKU = 'abstract_sku';

    /**
     * @see \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig::KEY_CONCRETE_SKU
     */
    protected const KEY_CONCRETE_SKU = 'concrete_sku';

    /**
     * @see \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig::KEY_STORE
     */
    protected const KEY_STORE = 'store';

    /**
     * @see \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig::KEY_CURRENCY
     */
    protected const KEY_CURRENCY = 'currency';

    /**
     * @see \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig::KEY_PRICE_TYPE
     */
    protected const KEY_PRICE_TYPE = 'price_type';

    /**
     * @see \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig::KEY_VALUE_NET
     */
    protected const KEY_VALUE_NET = 'value_net';

    /**
     * @see \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig::KEY_VALUE_GROSS
     */
    protected const KEY_VALUE_GROSS = 'value_gross';

    /**
     * @see \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig::KEY_FROM_INCLUDED
     */
    protected const KEY_FROM_INCLUDED = 'from_included';

    /**
     * @see \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig::KEY_TO_INCLUDED
     */
    protected const KEY_TO_INCLUDED = 'to_included';

    protected const KEY_ID_PRICE_PRODUCT_SCHEDULE = 'ID';

    protected const FILE_MAX_SIZE = '50M';
    protected const FILE_MIME_TYPES = ['text/csv', 'text/plain'];

    /**
     * @return string[]
     */
    public function getFieldsList(): array
    {
        return [
            static::KEY_ID_PRICE_PRODUCT_SCHEDULE,
            static::KEY_ABSTRACT_SKU,
            static::KEY_CONCRETE_SKU,
            static::KEY_STORE,
            static::KEY_CURRENCY,
            static::KEY_PRICE_TYPE,
            static::KEY_VALUE_NET,
            static::KEY_VALUE_GROSS,
            static::KEY_FROM_INCLUDED,
            static::KEY_TO_INCLUDED,
        ];
    }

    /**
     * @return string
     */
    public function getDefaultSortFieldForSuccessTable(): string
    {
        return static::KEY_ID_PRICE_PRODUCT_SCHEDULE;
    }

    /**
     * @return string
     */
    public function getIdPriceProductScheduleKey(): string
    {
        return static::KEY_ID_PRICE_PRODUCT_SCHEDULE;
    }

    /**
     * @return string
     */
    public function getAbstractSkuKey(): string
    {
        return static::KEY_ABSTRACT_SKU;
    }

    /**
     * @return string
     */
    public function getConcreteSkuKey(): string
    {
        return static::KEY_CONCRETE_SKU;
    }

    /**
     * @return string
     */
    public function getStoreKey(): string
    {
        return static::KEY_STORE;
    }

    /**
     * @return string
     */
    public function getCurrencyKey(): string
    {
        return static::KEY_CURRENCY;
    }

    /**
     * @return string
     */
    public function getPriceTypeKey(): string
    {
        return static::KEY_PRICE_TYPE;
    }

    /**
     * @return string
     */
    public function getValueNetKey(): string
    {
        return static::KEY_VALUE_NET;
    }

    /**
     * @return string
     */
    public function getValueGrossKey(): string
    {
        return static::KEY_VALUE_GROSS;
    }

    /**
     * @return string
     */
    public function getFromIncludedKey(): string
    {
        return static::KEY_FROM_INCLUDED;
    }

    /**
     * @return string
     */
    public function getToIncludedKey(): string
    {
        return static::KEY_TO_INCLUDED;
    }

    /**
     * @return string
     */
    public function getMaxFileSize(): string
    {
        return static::FILE_MAX_SIZE;
    }

    /**
     * @return array
     */
    public function getFileMimeTypes(): array
    {
        return static::FILE_MIME_TYPES;
    }
}
