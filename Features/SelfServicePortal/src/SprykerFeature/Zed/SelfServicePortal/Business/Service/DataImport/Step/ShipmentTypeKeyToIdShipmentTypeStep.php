<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\Step;

use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\DataSet\ProductShipmentTypeDataSetInterface;

class ShipmentTypeKeyToIdShipmentTypeStep implements DataImportStepInterface
{
    /**
     * @param \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery $shipmentTypeQuery
     */
    public function __construct(
        protected SpyShipmentTypeQuery $shipmentTypeQuery
    ) {
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $shipmentTypeKey = $dataSet[ProductShipmentTypeDataSetInterface::SHIPMENT_TYPE_KEY];

        $shipmentTypeEntity = $this->shipmentTypeQuery
            ->clear()
            ->filterByKey($shipmentTypeKey)
            ->findOne();

        if (!$shipmentTypeEntity) {
            throw new EntityNotFoundException(sprintf('Shipment type with key "%s" not found.', $shipmentTypeKey));
        }

        $dataSet[ProductShipmentTypeDataSetInterface::ID_SHIPMENT_TYPE] = $shipmentTypeEntity->getIdShipmentType();
    }
}
