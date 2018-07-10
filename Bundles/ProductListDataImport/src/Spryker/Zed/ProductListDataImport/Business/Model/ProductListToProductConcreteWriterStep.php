<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListDataImport\Business\Model;

use Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductList\Dependency\ProductListEvents;
use Spryker\Zed\ProductListDataImport\Business\Model\DataSet\ProductListDataSetInterface;

class ProductListToProductConcreteWriterStep extends PublishAwareStep implements DataImportStepInterface
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
        $productListProductConcreteEntity = SpyProductListProductConcreteQuery::create()
            ->filterByFkProductList($dataSet[ProductListDataSetInterface::ID_PRODUCT_LIST])
            ->filterByFkProduct($dataSet[ProductListDataSetInterface::ID_PRODUCT_CONCRETE])
            ->findOneOrCreate();

        $productListProductConcreteEntity->setFkProductList($dataSet[ProductListDataSetInterface::ID_PRODUCT_LIST])
            ->setFkProduct($dataSet[ProductListDataSetInterface::ID_PRODUCT_CONCRETE])
            ->save();

        $this->addPublishEvents(
            ProductListEvents::PRODUCT_LIST_PRODUCT_CONCRETE_PUBLISH,
            $productListProductConcreteEntity->getFkProduct()
        );
    }
}
