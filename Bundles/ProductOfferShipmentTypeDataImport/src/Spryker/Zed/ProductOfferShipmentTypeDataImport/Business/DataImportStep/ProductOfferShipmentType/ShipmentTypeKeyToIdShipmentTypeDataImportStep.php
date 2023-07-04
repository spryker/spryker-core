<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataImportStep\ProductOfferShipmentType;

use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataSet\ProductOfferShipmentTypeDataSetInterface;

class ShipmentTypeKeyToIdShipmentTypeDataImportStep implements DataImportStepInterface
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
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        /** @var string $shipmentTypeKey */
        $shipmentTypeKey = $dataSet[ProductOfferShipmentTypeDataSetInterface::COLUMN_SHIPMENT_TYPE_KEY];

        if (!isset(static::$shipmentTypeIdsIndexedByShipmentTypeKey[$shipmentTypeKey])) {
            static::$shipmentTypeIdsIndexedByShipmentTypeKey[$shipmentTypeKey] = $this->getIdShipmentTypeByKey($shipmentTypeKey);
        }

        $dataSet[ProductOfferShipmentTypeDataSetInterface::ID_SHIPMENT_TYPE] = static::$shipmentTypeIdsIndexedByShipmentTypeKey[$shipmentTypeKey];
    }

    /**
     * @param string $shipmentTypeKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdShipmentTypeByKey(string $shipmentTypeKey): int
    {
        /** @var int $idShipmentType */
        $idShipmentType = $this->getShipmentTypeQuery()
            ->select(static::COL_ID_SHIPMENT_TYPE)
            ->findOneByKey($shipmentTypeKey);

        if (!$idShipmentType) {
            throw new EntityNotFoundException(
                sprintf('Could not find shipment type by key "%s"', $shipmentTypeKey),
            );
        }

        return $idShipmentType;
    }

    /**
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery
     */
    protected function getShipmentTypeQuery(): SpyShipmentTypeQuery
    {
        return SpyShipmentTypeQuery::create();
    }
}
