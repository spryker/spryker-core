<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentTypeServicePointDataImport\Business\DataSet;

interface ShipmentTypeServiceTypeDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_SHIPMENT_TYPE_KEY = 'shipment_type_key';

    /**
     * @var string
     */
    public const COLUMN_SERVICE_TYPE_KEY = 'service_type_key';

    /**
     * @var string
     */
    public const SHIPMENT_TYPE_UUID = 'shipment_type_uuid';

    /**
     * @var string
     */
    public const SERVICE_TYPE_UUID = 'service_type_uuid';
}
