<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentTypeDataImport\Business\DataImportStep\ShipmentMethodShipmentType;

use Orm\Zed\Shipment\Persistence\Map\SpyShipmentMethodTableMap;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentTypeDataImport\Business\DataSet\ShipmentMethodShipmentTypeDataSetInterface;

class ShipmentMethodKeyToIdShipmentMethodDataImportStep implements DataImportStepInterface
{
    /**
     * @var array<string, int>
     */
    protected array $shipmentMethodIdsIndexedByKey = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        /** @var string $shipmentMethodKey */
        $shipmentMethodKey = $dataSet[ShipmentMethodShipmentTypeDataSetInterface::COLUMN_SHIPMENT_METHOD_KEY];
        if (!$shipmentMethodKey) {
            throw new InvalidDataException(
                sprintf('"%s" is required.', ShipmentMethodShipmentTypeDataSetInterface::COLUMN_SHIPMENT_METHOD_KEY),
            );
        }

        if (!isset($this->shipmentMethodIdsIndexedByKey[$shipmentMethodKey])) {
            $this->shipmentMethodIdsIndexedByKey[$shipmentMethodKey] = $this->getIdShipmentMethodByKey($shipmentMethodKey);
        }

        $dataSet[ShipmentMethodShipmentTypeDataSetInterface::ID_SHIPMENT_METHOD] = $this->shipmentMethodIdsIndexedByKey[$shipmentMethodKey];
    }

    /**
     * @param string $shipmentMethodKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdShipmentMethodByKey(string $shipmentMethodKey): int
    {
        /** @var int $idShipmentMethod */
        $idShipmentMethod = $this->createShipmentMethodQuery()
            ->select(SpyShipmentMethodTableMap::COL_ID_SHIPMENT_METHOD)
            ->findOneByShipmentMethodKey($shipmentMethodKey);
        if (!$idShipmentMethod) {
            throw new EntityNotFoundException(
                sprintf('Could not find Shipment Method by key "%s"', $shipmentMethodKey),
            );
        }

        return $idShipmentMethod;
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    protected function createShipmentMethodQuery(): SpyShipmentMethodQuery
    {
        return SpyShipmentMethodQuery::create();
    }
}
