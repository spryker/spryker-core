<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDataImport\Business\ShipmentMethodStore\Writer\Step;

use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentDataImport\Business\ShipmentMethodStore\Writer\DataSet\ShipmentMethodStoreDataSetInterface;

class ShipmentMethodKeyToIdShipmentMethodStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected static $idShipmentMethodCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $shipmentMethodKey = $dataSet[ShipmentMethodStoreDataSetInterface::COL_SHIPMENT_METHOD_KEY];

        if (!$shipmentMethodKey) {
            throw new DataKeyNotFoundInDataSetException('Shipment method key is missing');
        }

        if (!isset(static::$idShipmentMethodCache[$shipmentMethodKey])) {
            $shipmentMethodEntity = SpyShipmentMethodQuery::create()
                ->filterByShipmentMethodKey($shipmentMethodKey)
                ->findOne();

            if ($shipmentMethodEntity === null) {
                throw new EntityNotFoundException(sprintf('Shipment method not found: %s', $shipmentMethodKey));
            }

            static::$idShipmentMethodCache[$shipmentMethodKey] = $shipmentMethodEntity->getIdShipmentMethod();
        }

        $dataSet[ShipmentMethodStoreDataSetInterface::COL_ID_SHIPMENT_METHOD] = static::$idShipmentMethodCache[$shipmentMethodKey];
    }
}
