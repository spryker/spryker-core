<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\DataSet\PriceProductScheduleDataSetInterface;

class AbstractSkuToIdProductAbstractStep implements DataImportStepInterface
{
    protected const EXCEPTION_MESSAGE = 'Could not find abstract product by sku "%s"';

    /**
     * @var int[]
     */
    protected $idProductAbstractCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productAbstractSku = $dataSet[PriceProductScheduleDataSetInterface::KEY_ABSTRACT_SKU];
        if (empty($productAbstractSku)) {
            return;
        }

        if (!isset($this->idProductAbstractCache[$productAbstractSku])) {
            /** @var int|null $idProduct */
            $idProduct = $this->createPriceProductScheduleListQuery()
                ->select(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT)
                ->findOneBySku($productAbstractSku);

            if ($idProduct === null) {
                throw new EntityNotFoundException(sprintf(static::EXCEPTION_MESSAGE, $productAbstractSku));
            }

            $this->idProductAbstractCache[$productAbstractSku] = $idProduct;
        }

        $dataSet[PriceProductScheduleDataSetInterface::FK_PRODUCT_ABSTRACT] = $this->idProductAbstractCache[$productAbstractSku];
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function createPriceProductScheduleListQuery(): SpyProductAbstractQuery
    {
        return SpyProductAbstractQuery::create();
    }
}
