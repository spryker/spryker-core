<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentTypeServicePointDataImport\Business\DataImportStep\ShipmentTypeServiceType;

use Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentTypeServicePointDataImport\Business\DataSet\ShipmentTypeServiceTypeDataSetInterface;

class ServiceTypeKeyToIdServiceTypeStep implements DataImportStepInterface
{
    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServiceTypeTableMap::COL_ID_SERVICE_TYPE
     *
     * @var string
     */
    protected const COL_ID_SERVICE_TYPE = 'spy_service_type.id_service_type';

    /**
     * @var array<string, int>
     */
    protected static array $serviceTypeIdsIndexedByServiceTypeKey = [];

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
        $serviceTypeKey = $dataSet[ShipmentTypeServiceTypeDataSetInterface::COLUMN_SERVICE_TYPE_KEY];
        if (!$serviceTypeKey) {
            throw new InvalidDataException(sprintf('"%s" is required.', ShipmentTypeServiceTypeDataSetInterface::COLUMN_SERVICE_TYPE_KEY));
        }

        $dataSet[ShipmentTypeServiceTypeDataSetInterface::ID_SERVICE_TYPE] = $this->getIdServiceType($serviceTypeKey);
    }

    /**
     * @param string $serviceTypeKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdServiceType(string $serviceTypeKey): int
    {
        if (isset(static::$serviceTypeIdsIndexedByServiceTypeKey[$serviceTypeKey])) {
            return static::$serviceTypeIdsIndexedByServiceTypeKey[$serviceTypeKey];
        }

        /** @var int|null $idServiceType */
        $idServiceType = $this->getServiceTypeQuery()
            ->select(static::COL_ID_SERVICE_TYPE)
            ->findOneByKey($serviceTypeKey);

        if (!$idServiceType) {
            throw new EntityNotFoundException(sprintf('Could not find service type by key "%s"', $serviceTypeKey));
        }

        static::$serviceTypeIdsIndexedByServiceTypeKey[$serviceTypeKey] = $idServiceType;

        return static::$serviceTypeIdsIndexedByServiceTypeKey[$serviceTypeKey];
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery
     */
    protected function getServiceTypeQuery(): SpyServiceTypeQuery
    {
        return SpyServiceTypeQuery::create();
    }
}
