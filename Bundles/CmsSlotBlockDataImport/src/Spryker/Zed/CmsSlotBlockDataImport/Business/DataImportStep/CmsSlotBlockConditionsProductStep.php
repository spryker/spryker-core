<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataSet\CmsSlotBlockDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsSlotBlockConditionsProductStep implements DataImportStepInterface
{
    protected const KEY_CONDITION_PRODUCT_CATEGORY = 'productCategory';
    protected const KEY_CONDITION_PRODUCT_CATEGORY_SKUS = 'skus';
    protected const KEY_CONDITION_PRODUCT_CATEGORY_PRODUCT_IDS = 'productIds';
    protected const KEY_CONDITION_PRODUCT_CATEGORY_ALL = 'all';

    protected const BULK_SELECT_CHUNK_SIZE = 1000;

    /**
     * @var array
     */
    protected $cachedProductAbstractSkusToIds = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $conditions = $dataSet[CmsSlotBlockDataSetInterface::CMS_SLOT_BLOCK_ALL_CONDITIONS];

        $conditions = $this->transformConditionProductAllValueToBoolean($conditions);
        $conditions = $this->transformProductSkusToIds($conditions, $dataSet);

        $dataSet[CmsSlotBlockDataSetInterface::CMS_SLOT_BLOCK_ALL_CONDITIONS] = $conditions;
    }

    /**
     * @param array $conditions
     *
     * @return array
     */
    protected function transformConditionProductAllValueToBoolean($conditions): array
    {
        if (!isset($conditions[static::KEY_CONDITION_PRODUCT_CATEGORY][static::KEY_CONDITION_PRODUCT_CATEGORY_ALL])) {
            return $conditions;
        }

        $conditions[static::KEY_CONDITION_PRODUCT_CATEGORY][static::KEY_CONDITION_PRODUCT_CATEGORY_ALL] = (bool)array_shift(
            $conditions[static::KEY_CONDITION_PRODUCT_CATEGORY][static::KEY_CONDITION_PRODUCT_CATEGORY_ALL]
        );

        return $conditions;
    }

    /**
     * @param array $conditions
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array
     */
    protected function transformProductSkusToIds(array $conditions, DataSetInterface $dataSet): array
    {
        if (!isset($conditions[static::KEY_CONDITION_PRODUCT_CATEGORY][static::KEY_CONDITION_PRODUCT_CATEGORY_SKUS])) {
            return $conditions;
        }

        $productAbstractIds = $this->getProductAbstractIds($conditions, $dataSet);

        $conditions[static::KEY_CONDITION_PRODUCT_CATEGORY][static::KEY_CONDITION_PRODUCT_CATEGORY_PRODUCT_IDS] = $productAbstractIds;
        unset($conditions[static::KEY_CONDITION_PRODUCT_CATEGORY][static::KEY_CONDITION_PRODUCT_CATEGORY_SKUS]);

        return $conditions;
    }

    /**
     * @param array $conditions
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int[]
     */
    protected function getProductAbstractIds(array $conditions, DataSetInterface $dataSet): array
    {
        $productAbstractSkus = array_unique($conditions[static::KEY_CONDITION_PRODUCT_CATEGORY][static::KEY_CONDITION_PRODUCT_CATEGORY_SKUS]);
        $productAbstractIds = $this->getProductAbstractIdsBySkus($productAbstractSkus);

        if (count($productAbstractIds) < count($productAbstractSkus)) {
            throw new EntityNotFoundException(sprintf(
                'Found invalid skus in a row with the provided template_path: "%s", slot_key: "%s", block_key: "%s".',
                $dataSet[CmsSlotBlockDataSetInterface::CMS_SLOT_TEMPLATE_PATH],
                $dataSet[CmsSlotBlockDataSetInterface::CMS_SLOT_KEY],
                $dataSet[CmsSlotBlockDataSetInterface::CMS_BLOCK_KEY]
            ));
        }

        return $productAbstractIds;
    }

    /**
     * @param string[] $productAbstractSkus
     *
     * @return int[]
     */
    protected function getProductAbstractIdsBySkus(array $productAbstractSkus): array
    {
        $productAbstractIds = [];

        foreach ($productAbstractSkus as $key => $productAbstractSku) {
            if (!isset($this->cachedProductAbstractSkusToIds[$productAbstractSku])) {
                continue;
            }

            $productAbstractIds[] = $this->cachedProductAbstractSkusToIds[$productAbstractSku];
            unset($productAbstractSkus[$key]);
        }

        if (!$productAbstractSkus) {
            return $productAbstractIds;
        }

        $productAbstractSkusChunks = array_chunk($productAbstractSkus, static::BULK_SELECT_CHUNK_SIZE);

        foreach ($productAbstractSkusChunks as $productAbstractSkusChunk) {
            $productAbstractIds = array_merge($productAbstractIds, $this->getProductAbstractIdsBySkusFromDb($productAbstractSkusChunk));
        }

        return $productAbstractIds;
    }

    /**
     * @param string[] $productAbstractSkus
     *
     * @return int[]
     */
    protected function getProductAbstractIdsBySkusFromDb(array $productAbstractSkus): array
    {
        $productAbstractEntity = SpyProductAbstractQuery::create()
            ->filterBySku_In($productAbstractSkus)
            ->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, SpyProductAbstractTableMap::COL_SKU])
            ->find();

        $productAbstractIds = [];

        foreach ($productAbstractEntity->toArray() as $productAbstract) {
            $idProductAbstract = (int)$productAbstract[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT];

            $productAbstractIds[] = $idProductAbstract;
            $this->cachedProductAbstractSkusToIds[$productAbstract[SpyProductAbstractTableMap::COL_SKU]] = $idProductAbstract;
        }

        return $productAbstractIds;
    }
}
