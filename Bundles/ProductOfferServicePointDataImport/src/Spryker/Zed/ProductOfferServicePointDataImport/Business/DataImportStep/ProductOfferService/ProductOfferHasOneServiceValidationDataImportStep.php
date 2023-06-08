<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferServicePointDataImport\Business\DataImportStep\ProductOfferService;

use Orm\Zed\ProductOfferServicePoint\Persistence\Base\SpyProductOfferServiceQuery;
use Orm\Zed\ProductOfferServicePoint\Persistence\Map\SpyProductOfferServiceTableMap;
use Orm\Zed\ServicePoint\Persistence\Base\SpyServiceQuery;
use Orm\Zed\ServicePoint\Persistence\Map\SpyServiceTableMap;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferServicePointDataImport\Business\DataSet\ProductOfferServiceDataSetInterface;

class ProductOfferHasOneServiceValidationDataImportStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $servicePointCount = $this->getServiceQuery()
            ->select(SpyServiceTableMap::COL_FK_SERVICE_POINT)
            ->filterByUuid_In($this->getServiceUuidsByProductOffer($dataSet))
            ->distinct()
            ->find()
            ->count();

        if ($servicePointCount > 1) {
            throw new InvalidDataException('Product offer must have only one service point');
        }
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServiceQuery
     */
    protected function getServiceQuery(): SpyServiceQuery
    {
        return SpyServiceQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductOfferServicePoint\Persistence\SpyProductOfferServiceQuery
     */
    protected function getProductOfferServiceQuery(): SpyProductOfferServiceQuery
    {
        return SpyProductOfferServiceQuery::create();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return list<string>
     */
    protected function getServiceUuidsByProductOffer(DataSetInterface $dataSet): array
    {
        $serviceUuids = $this->getProductOfferServiceQuery()
            ->select(SpyProductOfferServiceTableMap::COL_SERVICE_UUID)
            ->filterByProductOfferReference($dataSet[ProductOfferServiceDataSetInterface::COLUMN_PRODUCT_OFFER_REFERENCE])
            ->find()
            ->getData();

        $serviceUuids[] = $dataSet[ProductOfferServiceDataSetInterface::COLUMN_SERVICE_UUID];

        return array_unique($serviceUuids);
    }
}
