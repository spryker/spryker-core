<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\DataSet\PriceProductScheduleDataSetInterface;

class ConcreteSkuToIdProductStep implements DataImportStepInterface
{
    protected const EXCEPTION_MESSAGE = 'Could not find product by sku "%s"';

    /**
     * @var int[]
     */
    protected $idProductCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productConcreteSku = $dataSet[PriceProductScheduleDataSetInterface::KEY_CONCRETE_SKU];
        if (empty($productConcreteSku)) {
            return;
        }

        if (!isset($this->idProductCache[$productConcreteSku])) {
            $idProduct = $this->createProductQuery()
                ->select(SpyProductTableMap::COL_ID_PRODUCT)
                ->findOneBySku($productConcreteSku);

            if ($idProduct === null) {
                throw new EntityNotFoundException(sprintf(static::EXCEPTION_MESSAGE, $productConcreteSku));
            }

            $this->idProductCache[$productConcreteSku] = $idProduct;
        }

        $dataSet[PriceProductScheduleDataSetInterface::FK_PRODUCT_CONCRETE] = $this->idProductCache[$productConcreteSku];
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function createProductQuery(): SpyProductQuery
    {
        return SpyProductQuery::create();
    }
}
