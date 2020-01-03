<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDataImport\Business\Shipment\Writer\Step;

use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentDataImport\Business\Shipment\Writer\DataSet\ShipmentDataSetInterface;

class ShipmentMethodWriterStep implements DataImportStepInterface
{
    public const BULK_SIZE = 100;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $shipmentMethod = SpyShipmentMethodQuery::create()
            ->filterByShipmentMethodKey($dataSet[ShipmentDataSetInterface::COL_SHIPMENT_METHOD_KEY])
            ->findOneOrCreate();

        $shipmentMethod
            ->setFkShipmentCarrier($dataSet[ShipmentDataSetInterface::COL_ID_CARRIER])
            ->setName($dataSet[ShipmentDataSetInterface::COL_NAME])
            ->setFkTaxSet($dataSet[ShipmentDataSetInterface::COL_ID_TAX_SET])
            ->save();
    }
}
