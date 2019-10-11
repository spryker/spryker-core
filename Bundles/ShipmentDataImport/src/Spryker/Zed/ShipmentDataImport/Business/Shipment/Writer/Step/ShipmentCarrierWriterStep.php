<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDataImport\Business\Shipment\Writer\Step;

use Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentDataImport\Business\Shipment\Writer\DataSet\ShipmentDataSetInterface;

class ShipmentCarrierWriterStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected static $idShipmentCarrierCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $carrierName = $dataSet[ShipmentDataSetInterface::COL_CARRIER_NAME];

        if (!$carrierName) {
            throw new DataKeyNotFoundInDataSetException('Carrier name is missing');
        }

        if (!isset(static::$idShipmentCarrierCache[$carrierName])) {
            static::$idShipmentCarrierCache[$carrierName] = SpyShipmentCarrierQuery::create()
                ->filterByName($carrierName)
                ->findOneOrCreate()
                ->getIdShipmentCarrier();
        }

        $dataSet[ShipmentDataSetInterface::COL_ID_CARRIER] = static::$idShipmentCarrierCache[$carrierName];
    }
}
