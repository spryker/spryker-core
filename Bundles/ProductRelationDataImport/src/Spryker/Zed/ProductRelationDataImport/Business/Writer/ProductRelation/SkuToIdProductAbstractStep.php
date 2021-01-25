<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelation;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductRelationDataImport\Business\Writer\ProductRelation\DataSet\ProductRelationDataSetInterface;

class SkuToIdProductAbstractStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected static $idProductAbstractCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $sku = $dataSet[ProductRelationDataSetInterface::COL_PRODUCT];

        if (!$sku) {
            throw new DataKeyNotFoundInDataSetException('SKU is missing');
        }

        if (!isset(static::$idProductAbstractCache[$sku])) {
            $productAbstractEntity = SpyProductAbstractQuery::create()
                ->filterBySku($sku)
                ->findOne();

            if ($productAbstractEntity === null) {
                throw new EntityNotFoundException(sprintf('Abstract product not found: %s', $sku));
            }

            static::$idProductAbstractCache[$sku] = $productAbstractEntity->getIdProductAbstract();
        }

        $dataSet[ProductRelationDataSetInterface::COL_ID_PRODUCT_ABSTRACT] = static::$idProductAbstractCache[$sku];
    }
}
