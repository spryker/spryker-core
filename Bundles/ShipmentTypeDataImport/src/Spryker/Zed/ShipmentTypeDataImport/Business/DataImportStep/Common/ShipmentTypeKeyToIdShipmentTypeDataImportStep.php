<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentTypeDataImport\Business\DataImportStep\Common;

use Orm\Zed\ShipmentType\Persistence\Map\SpyShipmentTypeTableMap;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ShipmentTypeKeyToIdShipmentTypeDataImportStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    protected string $dataSetColumnShipmentTypeKey;

    /**
     * @var string
     */
    protected string $dataSetColumnIdShipmentType;

    /**
     * @var array<string, int>
     */
    protected array $shipmentTypeIdsIndexedByKey = [];

    /**
     * @param string $dataSetColumnShipmentTypeKey
     * @param string $dataSetColumnIdShipmentType
     */
    public function __construct(string $dataSetColumnShipmentTypeKey, string $dataSetColumnIdShipmentType)
    {
        $this->dataSetColumnShipmentTypeKey = $dataSetColumnShipmentTypeKey;
        $this->dataSetColumnIdShipmentType = $dataSetColumnIdShipmentType;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        /** @var string $shipmentTypeKey */
        $shipmentTypeKey = $dataSet[$this->dataSetColumnShipmentTypeKey];
        if (!$shipmentTypeKey) {
            $dataSet[$this->dataSetColumnIdShipmentType] = null;

            return;
        }

        if (!isset($this->shipmentTypeIdsIndexedByKey[$shipmentTypeKey])) {
            $this->shipmentTypeIdsIndexedByKey[$shipmentTypeKey] = $this->getIdShipmentTypeByKey($shipmentTypeKey);
        }

        $dataSet[$this->dataSetColumnIdShipmentType] = $this->shipmentTypeIdsIndexedByKey[$shipmentTypeKey];
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
            ->select(SpyShipmentTypeTableMap::COL_ID_SHIPMENT_TYPE)
            ->findOneByKey($shipmentTypeKey);

        if (!$idShipmentType) {
            throw new EntityNotFoundException(
                sprintf('Could not find Shipment Type by key "%s"', $shipmentTypeKey),
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
