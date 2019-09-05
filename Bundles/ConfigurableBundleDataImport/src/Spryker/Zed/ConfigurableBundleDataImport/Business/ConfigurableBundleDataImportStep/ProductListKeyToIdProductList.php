<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleDataImport\Business\ConfigurableBundleDataImportStep;

use Orm\Zed\ProductList\Persistence\Map\SpyProductListTableMap;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\ConfigurableBundleDataImport\Business\DataSet\ConfigurableBundleTemplateSlotDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ProductListKeyToIdProductList implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected static $idProductListBuffer;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productListKey = $dataSet[ConfigurableBundleTemplateSlotDataSetInterface::COLUMN_PRODUCT_LIST_KEY];

        if (!isset(static::$idProductListBuffer[$productListKey])) {
            $idProductList = $this->createProductListQuery()
                ->select([SpyProductListTableMap::COL_ID_PRODUCT_LIST])
                ->findOneByKey($productListKey);

            if (!$idProductList) {
                throw new EntityNotFoundException(sprintf('Could not find product list by key "%s"', $productListKey));
            }

            static::$idProductListBuffer[$productListKey] = $idProductList;
        }

        $dataSet[ConfigurableBundleTemplateSlotDataSetInterface::ID_PRODUCT_LIST] = static::$idProductListBuffer[$productListKey];
    }

    /**
     * @module ProductList
     *
     * @return \Orm\Zed\ProductList\Persistence\SpyProductListQuery
     */
    protected function createProductListQuery(): SpyProductListQuery
    {
        return SpyProductListQuery::create();
    }
}
