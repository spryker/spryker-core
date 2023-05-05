<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentTypeDataImport\Business\DataSet;

interface ShipmentTypeStoreDataSetInterface
{
    /**
     * @var string
     */
    public const COLUMN_SHIPMENT_TYPE_KEY = 'shipment_type_key';

    /**
     * @var string
     */
    public const COLUMN_STORE_NAME = 'store_name';

    /**
     * @var string
     */
    public const ID_SHIPMENT_TYPE = 'id_shipment_type';

    /**
     * @var string
     */
    public const ID_STORE = 'id_store';
}
