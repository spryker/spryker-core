<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeDataImport\Business;

use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductAlternative\Dependency\ProductAlternativeEvents;
use Spryker\Zed\ProductAlternativeDataImport\Business\ProductAlternativeDataSet\ProductAlternativeDataSetInterface;

class ProductAlternativeWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productAlternativeQuery = SpyProductAlternativeQuery::create()
            ->filterByFkProduct($dataSet[ProductAlternativeDataSetInterface::FK_PRODUCT]);

        if ($dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_SKU]) {
            $this->saveConcreteAlternative($dataSet, $productAlternativeQuery);
        }

        if ($dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_SKU]) {
            $this->saveAbstractAlternative($dataSet, $productAlternativeQuery);
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery $productAlternativeQuery
     *
     * @return void
     */
    protected function saveConcreteAlternative(DataSetInterface $dataSet, SpyProductAlternativeQuery $productAlternativeQuery): void
    {
        $productAlternativeEntity = $productAlternativeQuery->filterByFkProductConcreteAlternative(
            $dataSet[ProductAlternativeDataSetInterface::FK_PRODUCT_CONCRETE_ALTERNATIVE]
        )
            ->findOneOrCreate()
            ->setFkProductConcreteAlternative(
                $dataSet[ProductAlternativeDataSetInterface::FK_PRODUCT_CONCRETE_ALTERNATIVE]
            );
        $productAlternativeEntity->save();
        $this->addPublishEvents(
            ProductAlternativeEvents::PRODUCT_REPLACEMENT_CONCRETE_PUBLISH,
            $productAlternativeEntity->getFkProductConcreteAlternative()
        );
        $this->addPublishEvents(
            ProductAlternativeEvents::PRODUCT_ALTERNATIVE_PUBLISH,
            $productAlternativeEntity->getFkProduct()
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery $productAlternativeQuery
     *
     * @return void
     */
    protected function saveAbstractAlternative(DataSetInterface $dataSet, SpyProductAlternativeQuery $productAlternativeQuery): void
    {
        $productAlternativeEntity = $productAlternativeQuery->filterByFkProductAbstractAlternative(
            $dataSet[ProductAlternativeDataSetInterface::FK_PRODUCT_ABSTRACT_ALTERNATIVE]
        )
            ->findOneOrCreate()
            ->setFkProductAbstractAlternative(
                $dataSet[ProductAlternativeDataSetInterface::FK_PRODUCT_ABSTRACT_ALTERNATIVE]
            );
        $productAlternativeEntity->save();
        $this->addPublishEvents(
            ProductAlternativeEvents::PRODUCT_REPLACEMENT_ABSTRACT_PUBLISH,
            $productAlternativeEntity->getFkProductAbstractAlternative()
        );
        $this->addPublishEvents(
            ProductAlternativeEvents::PRODUCT_ALTERNATIVE_PUBLISH,
            $productAlternativeEntity->getFkProduct()
        );
    }
}
