<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductQuantityDataImport\Business\Model;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductQuantity\Persistence\SpyProductQuantity;
use Orm\Zed\ProductQuantity\Persistence\SpyProductQuantityQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductQuantity\Dependency\ProductQuantityEvents;
use Spryker\Zed\ProductQuantityDataImport\Business\Exception\EntityNotFoundException;

class ProductQuantityDataImportWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    protected const DEFAULT_MAX = null;
    protected const DEFAULT_INTERVAL = 1;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet = $this->filterDataSet($dataSet);

        $spyProductQuantityEntity = $this->saveProductQuantity($dataSet);

        $this->addPublishEvents(ProductQuantityEvents::PRODUCT_QUANTITY_PUBLISH, $spyProductQuantityEntity->getFkProduct());
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    protected function filterDataSet(DataSetInterface $dataSet): DataSetInterface
    {
        if ($dataSet[ProductQuantityDataImportDataSet::COLUMN_QUANTITY_INTERVAL] === "") {
            $dataSet[ProductQuantityDataImportDataSet::COLUMN_QUANTITY_INTERVAL] = static::DEFAULT_INTERVAL;
        }

        if ($dataSet[ProductQuantityDataImportDataSet::COLUMN_QUANTITY_MIN] === "") {
            $dataSet[ProductQuantityDataImportDataSet::COLUMN_QUANTITY_MIN] = $dataSet[ProductQuantityDataImportDataSet::COLUMN_QUANTITY_INTERVAL];
        }

        if ($dataSet[ProductQuantityDataImportDataSet::COLUMN_QUANTITY_MAX] === "") {
            $dataSet[ProductQuantityDataImportDataSet::COLUMN_QUANTITY_MAX] = static::DEFAULT_MAX;
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

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\ProductQuantity\Persistence\SpyProductQuantity
     */
    protected function saveProductQuantity(DataSetInterface $dataSet): SpyProductQuantity
    {
        $idProduct = $this->getIdProductBySku($dataSet[ProductQuantityDataImportDataSet::COLUMN_CONCRETE_SKU]);

        $spyProductQuantityEntity = SpyProductQuantityQuery::create()
            ->filterByFkProduct($idProduct)
            ->findOneOrCreate();

        $spyProductQuantityEntity
            ->setQuantityMin($dataSet[ProductQuantityDataImportDataSet::COLUMN_QUANTITY_MIN])
            ->setQuantityMax($dataSet[ProductQuantityDataImportDataSet::COLUMN_QUANTITY_MAX])
            ->setQuantityInterval($dataSet[ProductQuantityDataImportDataSet::COLUMN_QUANTITY_INTERVAL])
            ->save();

        return $spyProductQuantityEntity;
    }
}
