<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentTypeDataImport\Business\DataImportStep\ShipmentTypeStore;

use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentTypeDataImport\Business\DataSet\ShipmentTypeStoreDataSetInterface;
use Spryker\Zed\ShipmentTypeDataImport\Business\Validator\DataSetValidatorInterface;

class ShipmentTypeStoreWriteDataImportStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\ShipmentTypeDataImport\Business\Validator\DataSetValidatorInterface
     */
    protected DataSetValidatorInterface $dataSetValidator;

    /**
     * @param \Spryker\Zed\ShipmentTypeDataImport\Business\Validator\DataSetValidatorInterface $dataSetValidator
     */
    public function __construct(DataSetValidatorInterface $dataSetValidator)
    {
        $this->dataSetValidator = $dataSetValidator;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->dataSetValidator->assertNoEmptyColumns($dataSet);

        $shipmentTypeStoreEntity = $this->getShipmentTypeStoreQuery()
            ->filterByFkShipmentType($dataSet[ShipmentTypeStoreDataSetInterface::ID_SHIPMENT_TYPE])
            ->filterByFkStore($dataSet[ShipmentTypeStoreDataSetInterface::ID_STORE])
            ->findOneOrCreate();
        $shipmentTypeStoreEntity
            ->fromArray($dataSet->getArrayCopy())
            ->save();
    }

    /**
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeStoreQuery
     */
    protected function getShipmentTypeStoreQuery(): SpyShipmentTypeStoreQuery
    {
        return SpyShipmentTypeStoreQuery::create();
    }
}
