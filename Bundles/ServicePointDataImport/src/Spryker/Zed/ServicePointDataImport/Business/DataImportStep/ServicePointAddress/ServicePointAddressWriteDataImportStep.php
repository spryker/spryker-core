<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ServicePointDataImport\Business\DataImportStep\ServicePointAddress;

use Orm\Zed\ServicePoint\Persistence\Base\SpyServicePointAddressQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ServicePointDataImport\Business\DataSet\ServicePointAddressDataSetInterface;

class ServicePointAddressWriteDataImportStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Shared\ServicePointSearch\ServicePointSearchConfig::SERVICE_POINT_PUBLISH
     *
     * @var string
     */
    protected const SERVICE_POINT_PUBLISH = 'ServicePoint.service_point.publish';

    /**
     * @var list<string>
     */
    protected const OPTIONAL_FIELDS = [
        ServicePointAddressDataSetInterface::COLUMN_ADDRESS3,
        ServicePointAddressDataSetInterface::COLUMN_ID_REGION,
        ServicePointAddressDataSetInterface::COLUMN_REGION_ISO2_CODE,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->assertDataSet($dataSet);
        $dataSet = $this->formatDataSet($dataSet);

        $servicePointAddressEntity = $this->getServicePointAddressQuery()
            ->filterByFkServicePoint($dataSet[ServicePointAddressDataSetInterface::COLUMN_ID_SERVICE_POINT])
            ->findOneOrCreate();

        $servicePointAddressEntity
            ->fromArray($dataSet->getArrayCopy())
            ->setFkCountry($dataSet[ServicePointAddressDataSetInterface::COLUMN_ID_COUNTRY])
            ->setFkRegion($dataSet[ServicePointAddressDataSetInterface::COLUMN_ID_REGION] ?? null)
            ->save();

        $this->addPublishEvents(static::SERVICE_POINT_PUBLISH, $dataSet[ServicePointAddressDataSetInterface::COLUMN_ID_SERVICE_POINT]);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    protected function formatDataSet(DataSetInterface $dataSet): DataSetInterface
    {
        foreach ($dataSet as $column => $value) {
            if ($value !== '') {
                continue;
            }

            $dataSet->offsetSet($column, null);
        }

        return $dataSet;
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
            if ($value === '' && !in_array($column, static::OPTIONAL_FIELDS, true)) {
                throw new InvalidDataException(
                    sprintf('"%s" is required.', $column),
                );
            }
        }
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\Base\SpyServicePointAddressQuery
     */
    protected function getServicePointAddressQuery(): SpyServicePointAddressQuery
    {
        return SpyServicePointAddressQuery::create();
    }
}
