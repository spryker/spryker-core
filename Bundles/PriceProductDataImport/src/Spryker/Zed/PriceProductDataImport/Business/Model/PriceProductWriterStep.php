<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\PriceProductDataImport\Business\Model;

use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\PriceProductDataImport\Business\Model\DataSet\PriceProductDataSet;
use Spryker\Zed\Product\Dependency\ProductEvents;

class PriceProductWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $priceTypeEntity = SpyPriceTypeQuery::create()
            ->filterByName($dataSet[PriceProductDataSet::KEY_PRICE_TYPE])
            ->findOneOrCreate();

        if ($priceTypeEntity->isNew() || $priceTypeEntity->isModified()) {
            $priceTypeEntity->setPriceModeConfiguration(SpyPriceTypeTableMap::COL_PRICE_MODE_CONFIGURATION_BOTH);
            $priceTypeEntity->save();
        }

        $priceProductQuery = SpyPriceProductQuery::create();
        $priceProductQuery->filterByFkPriceType($priceTypeEntity->getIdPriceType());

        if (empty($dataSet[PriceProductDataSet::KEY_ABSTRACT_SKU]) && empty($dataSet[PriceProductDataSet::KEY_CONCRETE_SKU])) {
            throw new DataKeyNotFoundInDataSetException(sprintf(
                'One of "%s" or "%s" must be in the data set. Given: "%s"',
                PriceProductDataSet::KEY_ABSTRACT_SKU,
                PriceProductDataSet::KEY_CONCRETE_SKU,
                implode(', ', array_keys($dataSet->getArrayCopy()))
            ));
        }

        if (!empty($dataSet[PriceProductDataSet::KEY_ABSTRACT_SKU])) {
            $priceProductQuery->filterByFkProductAbstract($dataSet[PriceProductDataSet::ID_PRODUCT_ABSTRACT]);
            $this->addPublishEvents(PriceProductEvents::PRICE_ABSTRACT_PUBLISH, $dataSet[PriceProductDataSet::ID_PRODUCT_ABSTRACT]);
            $this->addPublishEvents(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $dataSet[PriceProductDataSet::ID_PRODUCT_ABSTRACT]);
        } else {
            $this->addPublishEvents(PriceProductEvents::PRICE_CONCRETE_PUBLISH, $dataSet[PriceProductDataSet::ID_PRODUCT_CONCRETE]);
            $priceProductQuery->filterByFkProduct($dataSet[PriceProductDataSet::ID_PRODUCT_CONCRETE]);
        }

        $productPriceEntity = $priceProductQuery->findOneOrCreate();
        $productPriceEntity->save();

        $priceProductStoreEntity = SpyPriceProductStoreQuery::create()
            ->filterByFkStore($dataSet[PriceProductDataSet::ID_STORE])
            ->filterByFkCurrency($dataSet[PriceProductDataSet::ID_CURRENCY])
            ->filterByFkPriceProduct($productPriceEntity->getPrimaryKey())
            ->findOneOrCreate();

        $priceProductStoreEntity->setGrossPrice($dataSet[PriceProductDataSet::KEY_PRICE_GROSS]);
        $priceProductStoreEntity->setNetPrice($dataSet[PriceProductDataSet::KEY_PRICE_NET]);
        $priceProductStoreEntity->setPriceData($dataSet[PriceProductDataSet::KEY_PRICE_DATA]);
        $priceProductStoreEntity->setPriceDataChecksum($dataSet[PriceProductDataSet::KEY_PRICE_DATA_CHECKSUM]);

        $priceProductStoreEntity->save();
    }
}
