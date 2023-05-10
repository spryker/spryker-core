<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ServicePointDataImport\Business\DataImportStep\ServiceType;

use Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ServicePointDataImport\Business\DataSet\ServiceTypeDataSetInterface;

class ServiceTypeWriteDataImportStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->assertDataSet($dataSet);

        $serviceTypeEntity = $this->getServiceTypeQuery()
            ->filterByKey($dataSet[ServiceTypeDataSetInterface::COLUMN_KEY])
            ->findOneOrCreate();

        $serviceTypeEntity
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
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery
     */
    protected function getServiceTypeQuery(): SpyServiceTypeQuery
    {
        return SpyServiceTypeQuery::create();
    }
}
