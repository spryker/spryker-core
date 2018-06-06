<?php
/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListDataImport\Business\Model\Step;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductListDataImport\Business\EntityNotFoundException;
use Spryker\Zed\ProductListDataImport\Business\Model\DataSet\ProductListDataSetInterface;

class ProductListToProductConcreteWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->saveProductListProductConcrete($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function saveProductListProductConcrete(DataSetInterface $dataSet): void
    {
        $idProductList = $this->getIdProductListByKey($dataSet[ProductListDataSetInterface::PRODUCT_LIST_KEY]);
        $idProduct = $this->getIdProductBySku($dataSet[ProductListDataSetInterface::CONCRETE_SKU]);

        $productListProductConcreteEntity = SpyProductListProductConcreteQuery::create()
            ->filterByFkProductList($idProductList)
            ->filterByFkProduct($idProduct)
            ->findOneOrCreate();

        $productListProductConcreteEntity->setFkProductList($idProductList)
            ->setFkProduct($idProduct)
            ->save();
    }

    /**
     * @param string $productConcreteSku
     *
     * @throws \Spryker\Zed\ProductListDataImport\Business\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdProductBySku(string $productConcreteSku): int
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
     * @param string $productListKey
     *
     * @throws \Spryker\Zed\ProductListDataImport\Business\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdProductListByKey(string $productListKey): int
    {
        $productListEntity = SpyProductListQuery::create()
            ->filterByKey($productListKey)
            ->findOne();

        if (!$productListEntity) {
            throw new EntityNotFoundException(
                sprintf('Product List with "%s" key was not found during data import', $productListKey)
            );
        }

        return $productListEntity->getIdProductList();
    }
}
