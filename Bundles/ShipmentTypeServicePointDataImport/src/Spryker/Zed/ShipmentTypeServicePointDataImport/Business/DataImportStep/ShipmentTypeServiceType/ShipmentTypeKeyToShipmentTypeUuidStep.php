<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentTypeServicePointDataImport\Business\DataImportStep\ShipmentTypeServiceType;

use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentTypeServicePointDataImport\Business\DataSet\ShipmentTypeServiceTypeDataSetInterface;

class ShipmentTypeKeyToShipmentTypeUuidStep implements DataImportStepInterface
{
    /**
     * @uses \Orm\Zed\ShipmentType\Persistence\Map\SpyShipmentTypeTableMap::COL_UUID
     *
     * @var string
     */
    protected const COL_UUID = 'spy_shipment_type.uuid';

    /**
     * @var array<string, string>
     */
    protected static array $shipmentTypeUuidsIndexedByShipmentTypeKey = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        /** @var string $shipmentTypeKey */
        $shipmentTypeKey = $dataSet[ShipmentTypeServiceTypeDataSetInterface::COLUMN_SHIPMENT_TYPE_KEY];
        if (!$shipmentTypeKey) {
            throw new InvalidDataException(sprintf('"%s" is required.', ShipmentTypeServiceTypeDataSetInterface::COLUMN_SHIPMENT_TYPE_KEY));
        }

        $dataSet[ShipmentTypeServiceTypeDataSetInterface::SHIPMENT_TYPE_UUID] = $this->getShipmentTypeUuid($shipmentTypeKey);
    }

    /**
     * @param string $shipmentTypeKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return string
     */
    protected function getShipmentTypeUuid(string $shipmentTypeKey): string
    {
        if (isset(static::$shipmentTypeUuidsIndexedByShipmentTypeKey[$shipmentTypeKey])) {
            return static::$shipmentTypeUuidsIndexedByShipmentTypeKey[$shipmentTypeKey];
        }

        $uuidShipmentType = $this->getShipmentTypeQuery()
            ->select(static::COL_UUID)
            ->findOneByKey($shipmentTypeKey);

        if (!$uuidShipmentType) {
            throw new EntityNotFoundException(sprintf('Could not find shipment type by key "%s"', $shipmentTypeKey));
        }

        static::$shipmentTypeUuidsIndexedByShipmentTypeKey[$shipmentTypeKey] = $uuidShipmentType;

        return static::$shipmentTypeUuidsIndexedByShipmentTypeKey[$shipmentTypeKey];
    }

    /**
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery
     */
    protected function getShipmentTypeQuery(): SpyShipmentTypeQuery
    {
        return SpyShipmentTypeQuery::create();
    }
}
