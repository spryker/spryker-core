<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityDataImport\Business\Model;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductQuantity\Persistence\SpyProductQuantityQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductQuantity\Dependency\ProductQuantityEvents;
use Spryker\Zed\ProductQuantityDataImport\Business\Exception\EntityNotFoundException;

class ProductQuantityDataImportWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet = $this->filterDataSet($dataSet);

        $idProduct = $this->getIdProductBySku($dataSet[ProductQuantityDataImportDataSet::KEY_CONCRETE_SKU]);

        $spyProductQuantityEntity = SpyProductQuantityQuery::create()
            ->filterByFkProduct($idProduct)
            ->findOneOrCreate();

        $spyProductQuantityEntity
            ->setQuantityMin($dataSet[ProductQuantityDataImportDataSet::KEY_QUANTITY_MIN])
            ->setQuantityMax($dataSet[ProductQuantityDataImportDataSet::KEY_QUANTITY_MAX])
            ->setQuantityInterval($dataSet[ProductQuantityDataImportDataSet::KEY_QUANTITY_INTERVAL])
            ->save();

        $this->addPublishEvents(ProductQuantityEvents::PRODUCT_QUANTITY_PUBLISH, $spyProductQuantityEntity->getFkProduct());
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    protected function filterDataSet(DataSetInterface $dataSet): DataSetInterface
    {
        if ($dataSet[ProductQuantityDataImportDataSet::KEY_QUANTITY_INTERVAL] === "") {
            $dataSet[ProductQuantityDataImportDataSet::KEY_QUANTITY_INTERVAL] = ProductQuantityDataImportDataSet::DEFAULT_INTERVAL;
        }

        if ($dataSet[ProductQuantityDataImportDataSet::KEY_QUANTITY_MIN] === "") {
            $dataSet[ProductQuantityDataImportDataSet::KEY_QUANTITY_MIN] = $dataSet[ProductQuantityDataImportDataSet::KEY_QUANTITY_INTERVAL];
        }

        if ($dataSet[ProductQuantityDataImportDataSet::KEY_QUANTITY_MAX] === "") {
            $dataSet[ProductQuantityDataImportDataSet::KEY_QUANTITY_MAX] = ProductQuantityDataImportDataSet::DEFAULT_MAX;
        }

        return $dataSet;
    }

    /**
     * @param string $productConcreteSku
     *
     * @throws \Spryker\Zed\ProductQuantityDataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdProductBySku($productConcreteSku): int
    {
        $spyProductEntity = SpyProductQuery::create()->findOneBySku($productConcreteSku);

        if (!$spyProductEntity) {
            throw new EntityNotFoundException(
                sprintf('Product concrete with "%s" SKU was not found during data import', $productConcreteSku)
            );
        }

        return $spyProductEntity->getIdProduct();
    }
}
