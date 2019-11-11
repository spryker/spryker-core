<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockCategoryConnector\Resolver;

class CategoryCmsSlotBlockConditionResolver implements CategoryCmsSlotBlockConditionResolverInterface
{
    // TODO: add @uses
    protected const CONDITIONS_DATA_KEY_ALL = 'all';

    // TODO: add @uses
    protected const CONDITIONS_DATA_KEY_CATEGORY_IDS = 'categoryIds';

    protected const CMS_SLOT_DATA_CATEGORY_KEY = 'idCategory';

    /**
     * @param array $conditionData
     * @param array $cmsSlotData
     *
     * @return bool
     */
    public function getIsCmsBlockVisibleInSlot(array $conditionData, array $cmsSlotData): bool
    {
        if ($this->getIsConditionDataKeyAll($conditionData)) {
            return true;
        }

        $idCategory = $this->getIdCategory($cmsSlotData);

        if (!$idCategory) {
            return false;
        }

        if ($this->getIsCategoryMeetConditions($idCategory, $conditionData)) {
            return true;
        }

        return false;
    }

    /**
     * @param array $conditionData
     *
     * @return bool
     */
    protected function getIsConditionDataKeyAll(array $conditionData): bool
    {
        if (!isset($conditionData[static::CONDITIONS_DATA_KEY_All])) {
            return false;
        }

        if (!$conditionData[static::CONDITIONS_DATA_KEY_All]) {
            return false;
        }

        return true;
    }

    /**
     * @param array $cmsSlotData
     *
     * @return int|null
     */
    protected function getIdCategory(array $cmsSlotData): ?int
    {
        return $cmsSlotData[static::CMS_SLOT_DATA_CATEGORY_KEY] ?? null;
    }

    /**
     * @param int $idCategory
     * @param array $conditionData
     *
     * @return bool
     */
    protected function getIsCategoryMeetConditions(int $idCategory, array $conditionData): bool
    {
        if (!isset($conditionData[static::CONDITIONS_DATA_KEY_CATEGORY_IDS])) {
            return false;
        }

        $conditionCategoryIds = $conditionData[static::CONDITIONS_DATA_KEY_CATEGORY_IDS];

        return in_array($idCategory, $conditionCategoryIds);
    }
}
