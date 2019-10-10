<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDataImport\Business\ShipmentStore\Writer\Step;

use Orm\Zed\Shipment\Persistence\SpyShipmentMethodStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentDataImport\Business\ShipmentStore\Writer\DataSet\ShipmentMethodStoreDataSetInterface;

class ShipmentMethodStoreWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $shipmentMethodStoreEntity = SpyShipmentMethodStoreQuery::create()
            ->filterByFkStore($dataSet[ShipmentMethodStoreDataSetInterface::COLUMN_ID_STORE])
            ->filterByFkShipmentMethod($dataSet[ShipmentMethodStoreDataSetInterface::COLUMN_ID_SHIPMENT_METHOD])
            ->findOneOrCreate();

        if (!$shipmentMethodStoreEntity->isNew()) {
            return;
        }

        $shipmentMethodStoreEntity->save();
    }
}
