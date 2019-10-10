<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDataImport\Business\Shipment\Writer\DataSet;

interface ShipmentDataSetInterface
{
    public const COL_CARRIER = 'carrier';
    public const COL_SHIPMENT_METHOD_KEY = 'shipment_method_key';
    public const COL_NAME = 'name';
    public const COL_ID_TAX_SET = 'idTaxSet';
    public const COL_TAX_SET_NAME = 'taxSetName';
}
