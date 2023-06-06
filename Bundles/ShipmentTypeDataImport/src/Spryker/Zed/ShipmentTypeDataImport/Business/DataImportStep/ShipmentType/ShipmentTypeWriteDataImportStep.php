<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentTypeDataImport\Business\DataImportStep\ShipmentType;

use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentTypeDataImport\Business\DataSet\ShipmentTypeDataSetInterface;
use Spryker\Zed\ShipmentTypeDataImport\Business\Validator\DataSetValidatorInterface;

class ShipmentTypeWriteDataImportStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Shared\ShipmentTypeStorage\ShipmentTypeStorageConfig::SHIPMENT_TYPE_PUBLISH
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_PUBLISH = 'ShipmentType.shipment_type.publish';

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

        $shipmentTypeEntity = $this->getShipmentTypeQuery()
            ->filterByKey($dataSet[ShipmentTypeDataSetInterface::COLUMN_KEY])
            ->findOneOrCreate();

        $shipmentTypeEntity->fromArray($dataSet->getArrayCopy());

        if (!$shipmentTypeEntity->isNew() && !$shipmentTypeEntity->isModified()) {
            return;
        }
        $shipmentTypeEntity->save();

        $this->addPublishEvents(static::SHIPMENT_TYPE_PUBLISH, $shipmentTypeEntity->getIdShipmentType());
    }

    /**
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery
     */
    protected function getShipmentTypeQuery(): SpyShipmentTypeQuery
    {
        return SpyShipmentTypeQuery::create();
    }
}
