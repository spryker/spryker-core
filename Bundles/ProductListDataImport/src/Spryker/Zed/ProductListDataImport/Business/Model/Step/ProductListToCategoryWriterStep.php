<?php
/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListDataImport\Business\Model\Step;

use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListCategoryQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductListDataImport\Business\EntityNotFoundException;
use Spryker\Zed\ProductListDataImport\Business\Model\DataSet\ProductListDataSetInterface;

class ProductListToCategoryWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->saveProductListCategory($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function saveProductListCategory(DataSetInterface $dataSet): void
    {
        $idProductList = $this->getIdProductListByKey($dataSet[ProductListDataSetInterface::PRODUCT_LIST_KEY]);
        $idCategory = $this->getIdCategoryByKey($dataSet[ProductListDataSetInterface::CATEGORY_KEY]);

        $productListCategoryEntity = SpyProductListCategoryQuery::create()
            ->filterByFkProductList($idProductList)
            ->filterByFkCategory($idCategory)
            ->findOneOrCreate();

        $productListCategoryEntity->setFkProductList($idProductList)
            ->setFkCategory($idCategory)
            ->save();
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

    /**
     * @param string $categoryKey
     *
     * @throws \Spryker\Zed\ProductListDataImport\Business\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCategoryByKey(string $categoryKey): int
    {
        $categoryEntity = SpyCategoryQuery::create()
            ->filterByCategoryKey($categoryKey)
            ->findOne();

        if (!$categoryEntity) {
            throw new EntityNotFoundException(
                sprintf('Category with "%s" key was not found during data import', $categoryKey)
            );
        }

        return $categoryEntity->getIdCategory();
    }
}
