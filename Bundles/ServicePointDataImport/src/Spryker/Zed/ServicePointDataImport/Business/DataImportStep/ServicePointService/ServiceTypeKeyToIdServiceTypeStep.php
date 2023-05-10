<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ServicePointDataImport\Business\DataImportStep\ServicePointService;

use Orm\Zed\ServicePoint\Persistence\Map\SpyServiceTypeTableMap;
use Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ServicePointDataImport\Business\DataSet\ServicePointServiceDataSetInterface;

class ServiceTypeKeyToIdServiceTypeStep implements DataImportStepInterface
{
    /**
     * @var array<string, int>
     */
    protected $serviceTypeIdsIndexedByKey = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        /** @var string $serviceTypeKey */
        $serviceTypeKey = $dataSet[ServicePointServiceDataSetInterface::COLUMN_SERVICE_TYPE_KEY];

        if (!$serviceTypeKey) {
            throw new InvalidDataException(
                sprintf('"%s" is required.', ServicePointServiceDataSetInterface::COLUMN_SERVICE_TYPE_KEY),
            );
        }

        if (!isset($this->serviceTypeIdsIndexedByKey[$serviceTypeKey])) {
            $this->serviceTypeIdsIndexedByKey[$serviceTypeKey] = $this->getIdServiceTypeByKey($serviceTypeKey);
        }

        $dataSet[ServicePointServiceDataSetInterface::COLUMN_ID_SERVICE_TYPE] = $this->serviceTypeIdsIndexedByKey[$serviceTypeKey];
    }

    /**
     * @param string $serviceTypeKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdServiceTypeByKey(string $serviceTypeKey): int
    {
        /** @var int $idServiceType */
        $idServiceType = $this->getServiceTypeQuery()
            ->select(SpyServiceTypeTableMap::COL_ID_SERVICE_TYPE)
            ->findOneByKey($serviceTypeKey);

        if (!$idServiceType) {
            throw new EntityNotFoundException(
                sprintf('Unable to find service type entity by key "%s"', $serviceTypeKey),
            );
        }

        return $idServiceType;
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery
     */
    protected function getServiceTypeQuery(): SpyServiceTypeQuery
    {
        return SpyServiceTypeQuery::create();
    }
}
