<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDataImport\Business\ShipmentMethodPrice\Writer\DataSet;

interface ShipmentMethodPriceDataSetInterface
{
    public const COL_STORE_NAME = 'store';
    public const COL_ID_STORE = 'fk_store';
    public const COL_CURRENCY_NAME = 'currency';
    public const COL_ID_CURRENCY = 'fk_currency';
    public const COL_SHIPMENT_METHOD_KEY = 'shipment_method_key';
    public const COL_NET_AMOUNT = 'value_net';
    public const COL_GROSS_AMOUNT = 'value_gross';
    public const COL_ID_SHIPMENT_METHOD = 'fk_shipment_method';
}
