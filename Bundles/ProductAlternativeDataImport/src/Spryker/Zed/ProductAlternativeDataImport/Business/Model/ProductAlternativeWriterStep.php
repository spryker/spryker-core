<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeDataImport\Business\Model;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternative;
use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery;
use Spryker\Zed\Api\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductAlternativeDataImport\Business\Exception\NoAlternativesException;
use Spryker\Zed\ProductAlternativeDataImport\Business\Model\DataSet\ProductAlternativeDataSetInterface;

class ProductAlternativeWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->saveProductAlternative($dataSet);
    }

    /**
     * @param string $productConcreteSku
     *
     * @throws \Spryker\Zed\Api\Business\Exception\EntityNotFoundException
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
     * @param string $productConcreteSku
     *
     * @throws \Spryker\Zed\Api\Business\Exception\EntityNotFoundException
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    public function getAlternativeConcreteProduct($productConcreteSku)
    {
        $spyProductConcreteEntity = SpyProductQuery::create()->findOneBySku($productConcreteSku);

        if (!$spyProductConcreteEntity) {
            throw new EntityNotFoundException(
                sprintf('Product concrete with "%s" SKU was not found during data import', $productConcreteSku)
            );
        }

        return $spyProductConcreteEntity;
    }

    /**
     * @param string $productAbstractSku
     *
     * @throws \Spryker\Zed\Api\Business\Exception\EntityNotFoundException
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    public function getAlternativeAbstractProduct($productAbstractSku)
    {
        $spyProductAbstractEntity = SpyProductAbstractQuery::create()->findOneBySku($productAbstractSku);

        if (!$spyProductAbstractEntity) {
            throw new EntityNotFoundException(
                sprintf('Product concrete with "%s" SKU was not found during data import', $productAbstractSku)
            );
        }

        return $spyProductAbstractEntity;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\ProductAlternative\Persistence\SpyProductAlternative
     */
    protected function saveProductAlternative(DataSetInterface $dataSet): SpyProductAlternative
    {
        $this->checkIfDataIsValid($dataSet);

        $idProduct = $this->getIdProductBySku($dataSet[ProductAlternativeDataSetInterface::COLUMN_CONCRETE_SKU]);
        $spyProductAlternativeEntity = SpyProductAlternativeQuery::create()
            ->filterByFkProduct($idProduct)
            ->findOneOrCreate();

        if ($dataSet[ProductAlternativeDataSetInterface::COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_SKU]) {
            $productConcreteAlternative = $this->getAlternativeConcreteProduct(
                $dataSet[ProductAlternativeDataSetInterface::COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_SKU]
            );

            $spyProductAlternativeEntity->setProductConcreteAlternative($productConcreteAlternative);
        }

        if ($dataSet[ProductAlternativeDataSetInterface::COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_SKU]) {
            $productAbstractAlternative = $this->getAlternativeAbstractProduct(
                $dataSet[ProductAlternativeDataSetInterface::COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_SKU]
            );

            $spyProductAlternativeEntity->setProductAbstractAlternative($productAbstractAlternative);
        }

        $spyProductAlternativeEntity->save();

        return $spyProductAlternativeEntity;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\ProductAlternativeDataImport\Business\Exception\NoAlternativesException
     *
     * @return void
     */
    public function checkIfDataIsValid(DataSetInterface $dataSet)
    {
        if (!$dataSet[ProductAlternativeDataSetInterface::COLUMN_ALTERNATIVE_PRODUCT_CONCRETE_SKU] && !$dataSet[ProductAlternativeDataSetInterface::COLUMN_ALTERNATIVE_PRODUCT_ABSTRACT_SKU]) {
            throw new NoAlternativesException(
                sprintf('Product concrete with "%s" SKU has neither concrete nor abstract alternative', $dataSet[ProductAlternativeDataSetInterface::COLUMN_CONCRETE_SKU])
            );
        }
    }
}
