<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui;

use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;

class PriceProductScheduleGuiConfig
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

    public function getImportFileToTransferFieldsMap(): array
    {
        return [
            self::KEY_ABSTRACT_SKU => PriceProductScheduleImportTransfer::SKU_PRODUCT_ABSTRACT,
            self::KEY_CONCRETE_SKU => PriceProductScheduleImportTransfer::SKU_PRODUCT,
            self::KEY_STORE => PriceProductScheduleImportTransfer::STORE_NAME,
            self::KEY_CURRENCY => PriceProductScheduleImportTransfer::CURRENCY_CODE,
            self::KEY_PRICE_TYPE => PriceProductScheduleImportTransfer::PRICE_TYPE_NAME,
            self::KEY_PRICE_NET => PriceProductScheduleImportTransfer::NET_AMOUNT,
            self::KEY_PRICE_GROSS => PriceProductScheduleImportTransfer::GROSS_AMOUNT,
            self::KEY_INCLUDED_FROM => PriceProductScheduleImportTransfer::ACTIVE_FROM,
            self::KEY_INCLUDED_TO => PriceProductScheduleImportTransfer::ACTIVE_TO,
        ];
    }
}
