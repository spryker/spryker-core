<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductLabelDataImport\Business\Writer\ProductLabel\DataSet\ProductLabelDataSetInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class SkusToProductAbstractIdsStep implements DataImportStepInterface
{
    protected const BULK_SIZE = 100;

    /**
     * @var int[]
     */
    protected static $idProductAbstractCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!$dataSet[ProductLabelDataSetInterface::COL_PRODUCT_ABSTRACT_SKUS]) {
            return;
        }

        $productAbstractSkus = explode(',', $dataSet[ProductLabelDataSetInterface::COL_PRODUCT_ABSTRACT_SKUS]);

        if (!$productAbstractSkus) {
            return;
        }

        $this->cacheProductAbstractIdsBySkus($this->getNotCachedProductAbstractSkus($productAbstractSkus));

        $productAbstractIds = [];
        foreach ($productAbstractSkus as $sku) {
            if (!isset(static::$idProductAbstractCache[$sku])) {
                throw new EntityNotFoundException(sprintf('Abstract product not found: %s', $sku));
            }

            $productAbstractIds[] = static::$idProductAbstractCache[$sku];
        }

        $dataSet[ProductLabelDataSetInterface::COL_PRODUCT_ABSTRACT_IDS] = $productAbstractIds;
    }

    /**
     * @param string[] $productAbstractSkus
     *
     * @return string[]
     */
    protected function getNotCachedProductAbstractSkus(array $productAbstractSkus): array
    {
        $notCachedSkus = [];
        foreach ($productAbstractSkus as $productAbstractSku) {
            if (!isset(static::$idProductAbstractCache[$productAbstractSku])) {
                $notCachedSkus[] = $productAbstractSku;
            }
        }

        return $notCachedSkus;
    }

    /**
     * @param string[] $productAbstractSkus
     *
     * @return void
     */
    protected function cacheProductAbstractIdsBySkus(array $productAbstractSkus): void
    {
        foreach (array_chunk($productAbstractSkus, static::BULK_SIZE) as $productAbstractSkusChunk) {
            $productAbstractEntities = SpyProductAbstractQuery::create()
                ->filterBySku($productAbstractSkusChunk, Criteria::IN)
                ->find();

            foreach ($productAbstractEntities as $productAbstractEntity) {
                static::$idProductAbstractCache[$productAbstractEntity->getSku()] = $productAbstractEntity->getIdProductAbstract();
            }
        }
    }
}
