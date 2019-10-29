<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep;

use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Spryker\Zed\CmsSlotBlockDataImport\Business\DataSet\CmsSlotBlockDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsSlotBlockConditionsCategoryStep implements DataImportStepInterface
{
    protected const KEY_CONDITION_PRODUCT = 'product';
    protected const KEY_CONDITION_CATEGORY = 'category';
    protected const KEY_CONDITION_CATEGORY_KEYS = 'category_key';
    protected const KEY_CONDITION_CATEGORY_IDS = 'categoryIds';
    protected const KEY_CONDITION_ALL = 'all';

    /**
     * @var array
     */
    protected $cachedCategoryKeysToIds = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $conditions = $dataSet[CmsSlotBlockDataSetInterface::CMS_SLOT_BLOCK_ALL_CONDITIONS];
        $conditions = $this->transformConditionCategoryAllValueToBoolean($conditions);

        if (isset($conditions[static::KEY_CONDITION_PRODUCT])) {
            $conditions[static::KEY_CONDITION_PRODUCT] = $this->transformCategoryKeysToIds($conditions[static::KEY_CONDITION_PRODUCT]);
        }

        if (isset($conditions[static::KEY_CONDITION_CATEGORY])) {
            $conditions[static::KEY_CONDITION_CATEGORY] = $this->transformCategoryKeysToIds($conditions[static::KEY_CONDITION_CATEGORY]);
        }

        $dataSet[CmsSlotBlockDataSetInterface::CMS_SLOT_BLOCK_ALL_CONDITIONS] = $conditions;
    }

    /**
     * @param array $conditions
     *
     * @return array
     */
    protected function transformConditionCategoryAllValueToBoolean(array $conditions): array
    {
        if (isset($conditions[static::KEY_CONDITION_CATEGORY][static::KEY_CONDITION_ALL])) {
            $conditions[static::KEY_CONDITION_CATEGORY][static::KEY_CONDITION_ALL] = (bool)array_shift($conditions[static::KEY_CONDITION_PRODUCT][static::KEY_CONDITION_ALL]);
        }

        return $conditions;
    }

    /**
     * @param array $conditions
     *
     * @return array
     */
    protected function transformCategoryKeysToIds(array $conditions): array
    {
        if (!isset($conditions[static::KEY_CONDITION_CATEGORY_KEYS])) {
            return $conditions;
        }

        $productCategoryIds = [];

        foreach ($conditions[static::KEY_CONDITION_CATEGORY_KEYS] as $categoryKey) {
            $productCategoryIds[] = $this->getCategoryIdByKey($categoryKey);
        }

        $conditions[static::KEY_CONDITION_CATEGORY_IDS] = $productCategoryIds;
        unset($conditions[static::KEY_CONDITION_CATEGORY_KEYS]);

        return $conditions;
    }

    /**
     * @param string $key
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getCategoryIdByKey(string $key): int
    {
        if (isset($this->cachedCategoryKeysToIds[$key])) {
            return $this->cachedCategoryKeysToIds[$key];
        }

        $categoryEntity = SpyCategoryQuery::create()
            ->filterByCategoryKey($key)
            ->findOne();

        if (!$categoryEntity) {
            throw new EntityNotFoundException(sprintf('Could not find Category ID by key "%s".', $key));
        }

        $idCategory = $categoryEntity->getIdCategory();
        $this->cachedCategoryKeysToIds[$categoryEntity->getCategoryKey()] = $idCategory;

        return $idCategory;
    }
}
