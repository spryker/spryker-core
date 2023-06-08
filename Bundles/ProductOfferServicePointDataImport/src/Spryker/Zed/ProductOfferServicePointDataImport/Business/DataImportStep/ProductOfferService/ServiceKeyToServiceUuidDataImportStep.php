<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferServicePointDataImport\Business\DataImportStep\ProductOfferService;

use Orm\Zed\ServicePoint\Persistence\Map\SpyServiceTableMap;
use Orm\Zed\ServicePoint\Persistence\SpyServiceQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferServicePointDataImport\Business\DataSet\ProductOfferServiceDataSetInterface;

class ServiceKeyToServiceUuidDataImportStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[ProductOfferServiceDataSetInterface::COLUMN_SERVICE_UUID] = $this->getServiceUuidByServiceKey(
            $dataSet[ProductOfferServiceDataSetInterface::COLUMN_SERVICE_KEY],
        );
    }

    /**
     * @param string $serviceKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return string
     */
    protected function getServiceUuidByServiceKey(string $serviceKey): string
    {
        /** @var string|null $serviceUuid */
        $serviceUuid = $this->getServiceQuery()
            ->select(SpyServiceTableMap::COL_UUID)
            ->findOneByKey($serviceKey);

        if (!$serviceUuid) {
            throw new EntityNotFoundException(
                sprintf('Could not find service by key "%s".', $serviceKey),
            );
        }

        return $serviceUuid;
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServiceQuery
     */
    protected function getServiceQuery(): SpyServiceQuery
    {
        return SpyServiceQuery::create();
    }
}
