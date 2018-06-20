<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListDataImport\Business\Model;

use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductList\Dependency\ProductListEvents;
use Spryker\Zed\ProductListDataImport\Business\Model\DataSet\ProductListDataSetInterface;

class ProductListWriterStep extends PublishAwareStep implements DataImportStepInterface
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
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function saveProductList(DataSetInterface $dataSet): void
    {
        $productListKey = $dataSet[ProductListDataSetInterface::PRODUCT_LIST_KEY];
        if (!$productListKey) {
            throw new InvalidDataException(sprintf('"%s" is required.', ProductListDataSetInterface::PRODUCT_LIST_KEY));
        }

        $productListEntity = SpyProductListQuery::create()
            ->filterByKey($productListKey)
            ->findOneOrCreate();

        $productListEntity->setKey($productListKey)
            ->setTitle($dataSet[ProductListDataSetInterface::PRODUCT_LIST_NAME])
            ->setType($dataSet[ProductListDataSetInterface::PRODUCT_LIST_TYPE])
            ->save();

        $this->addPublishEvents(
            ProductListEvents::PRODUCT_LIST_PUBLISH,
            $productListEntity->getIdProductList()
        );
    }
}
