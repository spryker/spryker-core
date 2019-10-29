<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataSet\CmsSlotBlockDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsSlotBlockConditionsProductStep implements DataImportStepInterface
{
    protected const KEY_CONDITION_PRODUCT = 'product';
    protected const KEY_CONDITION_PRODUCT_SKUS = 'skus';
    protected const KEY_CONDITION_PRODUCT_IDS = 'productIds';
    protected const KEY_CONDITION_PRODUCT_ALL = 'all';

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
        $conditions = $this->transformProductSkusToIds($conditions);

        $dataSet[CmsSlotBlockDataSetInterface::CMS_SLOT_BLOCK_ALL_CONDITIONS] = $conditions;
    }

    /**
     * @param array $conditions
     *
     * @return array
     */
    protected function transformConditionProductAllValueToBoolean($conditions)
    {
        if (isset($conditions[static::KEY_CONDITION_PRODUCT][static::KEY_CONDITION_PRODUCT_ALL])) {
            $conditions[static::KEY_CONDITION_PRODUCT][static::KEY_CONDITION_PRODUCT_ALL] = (bool)array_shift($conditions[static::KEY_CONDITION_PRODUCT][static::KEY_CONDITION_PRODUCT_ALL]);
        }

        return $conditions;
    }

    /**
     * @param array $conditions
     *
     * @return array
     */
    protected function transformProductSkusToIds(array $conditions): array
    {
        if (!isset($conditions[static::KEY_CONDITION_PRODUCT][static::KEY_CONDITION_PRODUCT_SKUS])) {
            return $conditions;
        }

        $productIds = [];

        foreach ($conditions[static::KEY_CONDITION_PRODUCT][static::KEY_CONDITION_PRODUCT_SKUS] as $sku) {
            $productIds[] = $this->getIdProductAbstractBySku($sku);
        }

        $conditions[static::KEY_CONDITION_PRODUCT][static::KEY_CONDITION_PRODUCT_IDS] = $productIds;
        unset($conditions[static::KEY_CONDITION_PRODUCT][static::KEY_CONDITION_PRODUCT_SKUS]);

        return $conditions;
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdProductAbstractBySku(string $sku): int
    {
        if (isset($this->cachedProductAbstractSkusToIds[$sku])) {
            return $this->cachedProductAbstractSkusToIds[$sku];
        }

        $productAbstractEntity = SpyProductAbstractQuery::create()
            ->filterBySku($sku)
            ->findOne();

        if (!$productAbstractEntity) {
            throw new EntityNotFoundException(sprintf('Could not find Product Abstract ID by sku "%s".', $sku));
        }

        $idProductAbstract = $productAbstractEntity->getIdProductAbstract();
        $this->cachedProductAbstractSkusToIds[$productAbstractEntity->getSku()] = $idProductAbstract;

        return $idProductAbstract;
    }
}
