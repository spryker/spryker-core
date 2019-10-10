<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDataImport\Business\ShipmentStore\Writer\DataSet;

interface ShipmentMethodStoreDataSetInterface
{
    public const COLUMN_SHIPMENT_METHOD_KEY = 'shipment_method_key';
    public const COLUMN_STORE_NAME = 'store';

    public const COLUMN_ID_SHIPMENT_METHOD = 'fk_shipment_method';
    public const COLUMN_ID_STORE = 'fk_store';
}
