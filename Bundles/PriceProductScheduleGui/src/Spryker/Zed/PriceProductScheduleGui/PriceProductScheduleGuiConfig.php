<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui;

use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class PriceProductScheduleGuiConfig extends AbstractBundleConfig
{
    protected const KEY_ABSTRACT_SKU = 'abstract_sku';
    protected const KEY_CONCRETE_SKU = 'concrete_sku';
    protected const KEY_STORE = 'store';
    protected const KEY_CURRENCY = 'currency';
    protected const KEY_PRICE_TYPE = 'price_type';

    protected const KEY_VALUE_NET = 'value_net';
    protected const KEY_VALUE_GROSS = 'value_gross';

    protected const KEY_FROM_INCLUDED = 'from_included';
    protected const KEY_TO_INCLUDED = 'to_included';

    protected const FILE_MAX_SIZE = '50M';

    /**
     * @return array
     */
    public function getImportFileToTransferFieldsMap(): array
    {
        return [
            static::KEY_ABSTRACT_SKU => PriceProductScheduleImportTransfer::SKU_PRODUCT_ABSTRACT,
            static::KEY_CONCRETE_SKU => PriceProductScheduleImportTransfer::SKU_PRODUCT,
            static::KEY_STORE => PriceProductScheduleImportTransfer::STORE_NAME,
            static::KEY_CURRENCY => PriceProductScheduleImportTransfer::CURRENCY_CODE,
            static::KEY_PRICE_TYPE => PriceProductScheduleImportTransfer::PRICE_TYPE_NAME,
            static::KEY_VALUE_NET => PriceProductScheduleImportTransfer::NET_AMOUNT,
            static::KEY_VALUE_GROSS => PriceProductScheduleImportTransfer::GROSS_AMOUNT,
            static::KEY_FROM_INCLUDED => PriceProductScheduleImportTransfer::ACTIVE_FROM,
            static::KEY_TO_INCLUDED => PriceProductScheduleImportTransfer::ACTIVE_TO,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldsList(): array
    {
        return [
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
}
