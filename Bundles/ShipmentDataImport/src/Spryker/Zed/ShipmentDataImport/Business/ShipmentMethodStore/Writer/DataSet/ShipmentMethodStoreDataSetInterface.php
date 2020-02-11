<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDataImport\Business\ShipmentMethodStore\Writer\DataSet;

interface ShipmentMethodStoreDataSetInterface
{
    public const COL_SHIPMENT_METHOD_KEY = 'shipment_method_key';
    public const COL_STORE_NAME = 'store';

    public const COL_ID_SHIPMENT_METHOD = 'fk_shipment_method';
    public const COL_ID_STORE = 'fk_store';
}
