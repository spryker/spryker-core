<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ServicePointDataImport\Business\DataImportStep\ServicePointService;

use Orm\Zed\ServicePoint\Persistence\SpyServicePointServiceQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ServicePointDataImport\Business\DataSet\ServicePointServiceDataSetInterface;

class ServicePointServiceWriteDataImportStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->assertDataSet($dataSet);

        /** @var string $servicePointServiceKey */
        $servicePointServiceKey = $dataSet[ServicePointServiceDataSetInterface::COLUMN_KEY];

        $servicePointServiceEntity = $this->getServicePointServiceQuery()
            ->filterByKey($servicePointServiceKey)
            ->filterByFkServicePoint($dataSet[ServicePointServiceDataSetInterface::COLUMN_ID_SERVICE_POINT])
            ->filterByFkServiceType($dataSet[ServicePointServiceDataSetInterface::COLUMN_ID_SERVICE_TYPE])
            ->findOneOrCreate();

        $servicePointServiceEntity
            ->fromArray($dataSet->getArrayCopy())
            ->save();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function assertDataSet(DataSetInterface $dataSet): void
    {
        foreach ($dataSet as $column => $value) {
            if ($value === '') {
                throw new InvalidDataException(
                    sprintf('"%s" is required.', $column),
                );
            }
        }
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointServiceQuery
     */
    protected function getServicePointServiceQuery(): SpyServicePointServiceQuery
    {
        return SpyServicePointServiceQuery::create();
    }
}
