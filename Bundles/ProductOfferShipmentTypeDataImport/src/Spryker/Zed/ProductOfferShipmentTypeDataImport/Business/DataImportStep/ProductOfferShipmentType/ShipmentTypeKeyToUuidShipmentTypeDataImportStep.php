<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataImportStep\ProductOfferShipmentType;

use Orm\Zed\ShipmentType\Persistence\Map\SpyShipmentTypeTableMap;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferShipmentTypeDataImport\Business\DataSet\ProductOfferShipmentTypeDataSetInterface;

class ShipmentTypeKeyToUuidShipmentTypeDataImportStep implements DataImportStepInterface
{
    /**
     * @var array<string, string>
     */
    protected static array $shipmentTypeUuidsIndexedByShipmentTypeKey = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        /** @var string $shipmentTypeKey */
        $shipmentTypeKey = $dataSet[ProductOfferShipmentTypeDataSetInterface::COLUMN_SHIPMENT_TYPE_KEY];

        if (!isset($this->shipmentTypeUuidsIndexedByShipmentTypeKey[$shipmentTypeKey])) {
            static::$shipmentTypeUuidsIndexedByShipmentTypeKey[$shipmentTypeKey] = $this->getUuidShipmentTypeByKey($shipmentTypeKey);
        }

        $dataSet[ProductOfferShipmentTypeDataSetInterface::UUID_SHIPMENT_TYPE] = static::$shipmentTypeUuidsIndexedByShipmentTypeKey[$shipmentTypeKey];
    }

    /**
     * @param string $shipmentTypeKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return string
     */
    protected function getUuidShipmentTypeByKey(string $shipmentTypeKey): string
    {
        /** @var string $uuidShipmentType */
        $uuidShipmentType = $this->getShipmentTypeQuery()
            ->select(SpyShipmentTypeTableMap::COL_UUID)
            ->findOneByKey($shipmentTypeKey);

        if (!$uuidShipmentType) {
            throw new EntityNotFoundException(
                sprintf('Could not find shipment type by key "%s"', $shipmentTypeKey),
            );
        }

        return $uuidShipmentType;
    }

    /**
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery
     */
    protected function getShipmentTypeQuery(): SpyShipmentTypeQuery
    {
        return SpyShipmentTypeQuery::create();
    }
}
