<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentDataImport\Business\ShipmentMethodStore\Writer\DataSet;

interface ShipmentMethodStoreDataSetInterface
{
    public const COL_SHIPMENT_METHOD_KEY = 'shipment_method_key';
    public const COL_STORE_NAME = 'store';

    public const COL_ID_SHIPMENT_METHOD = 'fk_shipment_method';
    public const COL_ID_STORE = 'fk_store';
}
