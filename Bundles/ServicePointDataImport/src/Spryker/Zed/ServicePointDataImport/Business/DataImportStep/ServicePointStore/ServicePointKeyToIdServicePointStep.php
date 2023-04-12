<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ServicePointDataImport\Business\DataImportStep\ServicePointStore;

use Orm\Zed\ServicePoint\Persistence\Map\SpyServicePointTableMap;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ServicePointDataImport\Business\DataSet\ServicePointStoreDataSetInterface;

class ServicePointKeyToIdServicePointStep implements DataImportStepInterface
{
    /**
     * @var array<string, int>
     */
    protected $servicePointIdsIndexedByKey = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        /**
         * @var string $servicePointKey
         */
        $servicePointKey = $dataSet[ServicePointStoreDataSetInterface::COLUMN_SERVICE_POINT_KEY];

        if (!$servicePointKey) {
            throw new InvalidDataException(
                sprintf('"%s" is required.', ServicePointStoreDataSetInterface::COLUMN_SERVICE_POINT_KEY),
            );
        }

        if (!isset($this->servicePointIdsIndexedByKey[$servicePointKey])) {
            $this->servicePointIdsIndexedByKey[$servicePointKey] = $this->getIdServicePointByKey($servicePointKey);
        }

        $dataSet[ServicePointStoreDataSetInterface::COLUMN_ID_SERVICE_POINT] = $this->servicePointIdsIndexedByKey[$servicePointKey];
    }

    /**
     * @param string $servicePointKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdServicePointByKey(string $servicePointKey): int
    {
        /**
         * @var int $idServicePoint
         */
        $idServicePoint = $this->getServicePointStoreQuery()
            ->select(SpyServicePointTableMap::COL_ID_SERVICE_POINT)
            ->findOneByKey($servicePointKey);

        if (!$idServicePoint) {
            throw new EntityNotFoundException(
                sprintf('Could not find Service Point by key "%s"', $servicePointKey),
            );
        }

        return $idServicePoint;
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery
     */
    protected function getServicePointStoreQuery(): SpyServicePointQuery
    {
        return SpyServicePointQuery::create();
    }
}
