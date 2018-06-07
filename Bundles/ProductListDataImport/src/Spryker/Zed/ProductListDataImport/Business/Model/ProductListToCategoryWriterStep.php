<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListDataImport\Business\Model;

use Orm\Zed\ProductList\Persistence\SpyProductListCategoryQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
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
        $productListCategoryEntity = SpyProductListCategoryQuery::create()
            ->filterByFkProductList($dataSet[ProductListDataSetInterface::ID_PRODUCT_LIST])
            ->filterByFkCategory($dataSet[ProductListDataSetInterface::ID_CATEGORY])
            ->findOneOrCreate();

        $productListCategoryEntity->setFkProductList($dataSet[ProductListDataSetInterface::ID_PRODUCT_LIST])
            ->setFkCategory($dataSet[ProductListDataSetInterface::ID_CATEGORY])
            ->save();
    }
}
