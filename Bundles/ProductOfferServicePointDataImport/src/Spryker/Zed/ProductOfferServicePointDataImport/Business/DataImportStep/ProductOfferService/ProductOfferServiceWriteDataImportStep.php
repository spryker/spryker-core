<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferServicePointDataImport\Business\DataImportStep\ProductOfferService;

use Orm\Zed\ProductOfferServicePoint\Persistence\Base\SpyProductOfferServiceQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferServicePointDataImport\Business\DataSet\ProductOfferServiceDataSetInterface;

class ProductOfferServiceWriteDataImportStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Shared\ProductOfferServicePointStorage\ProductOfferServicePointStorageConfig::PRODUCT_OFFER_SERVICE_PUBLISH
     *
     * @var string
     */
    protected const PRODUCT_OFFER_SERVICE_PUBLISH = 'ProductOfferService.product_offer_service.publish';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->assertDataSet($dataSet);

        $productOfferServiceEntity = $this->getProductOfferServiceQuery()
            ->filterByFkProductOffer($dataSet[ProductOfferServiceDataSetInterface::COLUMN_ID_PRODUCT_OFFER])
            ->filterByFkService($dataSet[ProductOfferServiceDataSetInterface::COLUMN_ID_SERVICE])
            ->findOneOrCreate();

        if ($productOfferServiceEntity->isNew()) {
            $productOfferServiceEntity->save();
        }

        $this->addPublishEvents(static::PRODUCT_OFFER_SERVICE_PUBLISH, $productOfferServiceEntity->getIdProductOfferService());
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
     * @return \Orm\Zed\ProductOfferServicePoint\Persistence\SpyProductOfferServiceQuery
     */
    protected function getProductOfferServiceQuery(): SpyProductOfferServiceQuery
    {
        return SpyProductOfferServiceQuery::create();
    }
}
