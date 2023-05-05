<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ServicePointDataImport\Business\DataImportStep\ServicePointStore;

use Orm\Zed\ServicePoint\Persistence\SpyServicePointStoreQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ServicePointDataImport\Business\DataSet\ServicePointStoreDataSetInterface;

class ServicePointStoreWriteDataImportStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Shared\ServicePointSearch\ServicePointSearchConfig::SERVICE_POINT_PUBLISH
     *
     * @var string
     */
    protected const SERVICE_POINT_PUBLISH = 'ServicePoint.service_point.publish';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->assertDataSet($dataSet);

        $servicePointStoreEntity = $this->getServicePointStoreQuery()
            ->filterByFkServicePoint($dataSet[ServicePointStoreDataSetInterface::COLUMN_ID_SERVICE_POINT])
            ->filterByFkStore($dataSet[ServicePointStoreDataSetInterface::COLUMN_ID_STORE])
            ->findOneOrCreate();

        $servicePointStoreEntity
            ->fromArray($dataSet->getArrayCopy())
            ->save();

        $this->addPublishEvents(static::SERVICE_POINT_PUBLISH, $dataSet[ServicePointStoreDataSetInterface::COLUMN_ID_SERVICE_POINT]);
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
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointStoreQuery
     */
    protected function getServicePointStoreQuery(): SpyServicePointStoreQuery
    {
        return SpyServicePointStoreQuery::create();
    }
}
