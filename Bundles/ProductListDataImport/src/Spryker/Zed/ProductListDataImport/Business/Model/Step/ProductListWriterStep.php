<?php
/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListDataImport\Business\Model\Step;

use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductListDataImport\Business\Model\DataSet\ProductListDataSetInterface;

class ProductListWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->saveProductList($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function saveProductList(DataSetInterface $dataSet): void
    {
        $productListEntity = SpyProductListQuery::create()
            ->filterByKey($dataSet[ProductListDataSetInterface::PRODUCT_LIST_KEY])
            ->findOneOrCreate();

        $productListEntity->setKey($dataSet[ProductListDataSetInterface::PRODUCT_LIST_KEY])
            ->setTitle($dataSet[ProductListDataSetInterface::PRODUCT_LIST_NAME])
            ->setType($dataSet[ProductListDataSetInterface::PRODUCT_LIST_TYPE])
            ->save();
    }
}
