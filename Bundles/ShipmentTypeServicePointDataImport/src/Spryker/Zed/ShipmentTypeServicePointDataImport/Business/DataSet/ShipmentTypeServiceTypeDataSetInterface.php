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
    public const ID_SHIPMENT_TYPE = 'id_shipment_type';

    /**
     * @var string
     */
    public const ID_SERVICE_TYPE = 'id_service_type';
}
