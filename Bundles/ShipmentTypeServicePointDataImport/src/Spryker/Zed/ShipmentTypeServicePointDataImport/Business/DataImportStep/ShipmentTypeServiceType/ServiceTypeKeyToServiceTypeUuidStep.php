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

class ServiceTypeKeyToServiceTypeUuidStep implements DataImportStepInterface
{
    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServiceTypeTableMap::COL_UUID
     *
     * @var string
     */
    protected const COL_UUID = 'spy_service_type.uuid';

    /**
     * @var array<string, string>
     */
    protected static array $serviceTypeUuidsIndexedByServiceTypeKey = [];

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

        $dataSet[ShipmentTypeServiceTypeDataSetInterface::SERVICE_TYPE_UUID] = $this->getServiceTypeUuid($serviceTypeKey);
    }

    /**
     * @param string $serviceTypeKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return string
     */
    protected function getServiceTypeUuid(string $serviceTypeKey): string
    {
        if (isset(static::$serviceTypeUuidsIndexedByServiceTypeKey[$serviceTypeKey])) {
            return static::$serviceTypeUuidsIndexedByServiceTypeKey[$serviceTypeKey];
        }

        $uuidServiceType = $this->getServiceTypeQuery()
            ->select(static::COL_UUID)
            ->findOneByKey($serviceTypeKey);

        if (!$uuidServiceType) {
            throw new EntityNotFoundException(sprintf('Could not find service type by key "%s"', $serviceTypeKey));
        }

        static::$serviceTypeUuidsIndexedByServiceTypeKey[$serviceTypeKey] = $uuidServiceType;

        return static::$serviceTypeUuidsIndexedByServiceTypeKey[$serviceTypeKey];
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServiceTypeQuery
     */
    protected function getServiceTypeQuery(): SpyServiceTypeQuery
    {
        return SpyServiceTypeQuery::create();
    }
}
