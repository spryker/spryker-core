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

class ShipmentTypeKeyToIdShipmentTypeStep implements DataImportStepInterface
{
    /**
     * @uses \Orm\Zed\ShipmentType\Persistence\Map\SpyShipmentTypeTableMap::COL_ID_SHIPMENT_TYPE
     *
     * @var string
     */
    protected const COL_ID_SHIPMENT_TYPE = 'spy_shipment_type.id_shipment_type';

    /**
     * @var array<string, int>
     */
    protected static array $shipmentTypeIdsIndexedByShipmentTypeKey = [];

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

        $dataSet[ShipmentTypeServiceTypeDataSetInterface::ID_SHIPMENT_TYPE] = $this->getIdShipmentType($shipmentTypeKey);
    }

    /**
     * @param string $shipmentTypeKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdShipmentType(string $shipmentTypeKey): int
    {
        if (isset(static::$shipmentTypeIdsIndexedByShipmentTypeKey[$shipmentTypeKey])) {
            return static::$shipmentTypeIdsIndexedByShipmentTypeKey[$shipmentTypeKey];
        }

        /** @var int|null $idShipmentType */
        $idShipmentType = $this->getShipmentTypeQuery()
            ->select(static::COL_ID_SHIPMENT_TYPE)
            ->findOneByKey($shipmentTypeKey);

        if (!$idShipmentType) {
            throw new EntityNotFoundException(sprintf('Could not find shipment type by key "%s"', $shipmentTypeKey));
        }

        static::$shipmentTypeIdsIndexedByShipmentTypeKey[$shipmentTypeKey] = $idShipmentType;

        return static::$shipmentTypeIdsIndexedByShipmentTypeKey[$shipmentTypeKey];
    }

    /**
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery
     */
    protected function getShipmentTypeQuery(): SpyShipmentTypeQuery
    {
        return SpyShipmentTypeQuery::create();
    }
}
