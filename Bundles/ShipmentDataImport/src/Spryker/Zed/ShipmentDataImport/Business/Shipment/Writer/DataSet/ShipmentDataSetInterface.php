<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentDataImport\Business\Shipment\Writer\DataSet;

interface ShipmentDataSetInterface
{
    /**
     * @var string
     */
    public const COL_CARRIER_NAME = 'carrier';
    /**
     * @var string
     */
    public const COL_ID_CARRIER = 'id_carrier';
    /**
     * @var string
     */
    public const COL_SHIPMENT_METHOD_KEY = 'shipment_method_key';
    /**
     * @var string
     */
    public const COL_NAME = 'name';
    /**
     * @var string
     */
    public const COL_ID_TAX_SET = 'id_tax_set;';
    /**
     * @var string
     */
    public const COL_TAX_SET_NAME = 'taxSetName';
}
