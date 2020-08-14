<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentDataImport\Business\ShipmentMethodStore\Writer\Step;

use Orm\Zed\Shipment\Persistence\SpyShipmentMethodStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentDataImport\Business\ShipmentMethodStore\Writer\DataSet\ShipmentMethodStoreDataSetInterface;

class ShipmentMethodStoreWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        SpyShipmentMethodStoreQuery::create()
            ->filterByFkStore($dataSet[ShipmentMethodStoreDataSetInterface::COL_ID_STORE])
            ->filterByFkShipmentMethod($dataSet[ShipmentMethodStoreDataSetInterface::COL_ID_SHIPMENT_METHOD])
            ->findOneOrCreate()
            ->save();
    }
}
