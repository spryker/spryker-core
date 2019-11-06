<?php
/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\CmsSlotBlockCategoryConnector\Resolver;

use Generated\Shared\Transfer\CmsBlockTransfer;

class CategoryCmsSlotBlockConditionResolver implements CategoryCmsSlotBlockConditionResolverInterface
{
    // TODO: add @uses
    protected const CONDITIONS_DATA_KEY_All = 'all';

    // TODO: add @uses
    protected const CONDITIONS_DATA_KEY_CATEGORY_IDS = 'categoryIds';

    protected const CMS_SLOT_DATA_CATEGORY_KEY = 'idCategory';

    public function isCmsBlockVisibleInSlot(
        CmsBlockTransfer $cmsBlockTransfer,
        array $conditionData,
        array $cmsSlotData
    ): bool {
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

    protected function getIsConditionDataKeyAll(array $conditionData): bool
    {
        if (!isset($conditionData[static::CONDITIONS_DATA_KEY_All])) {
            return false;
        }

        if ($conditionData[static::CONDITIONS_DATA_KEY_All] !== 1) {
            return false;
        }

        return true;
    }

    protected function getIdCategory(array $cmsSlotData): ?int
    {
        if (!isset($cmsSlotData[static::CMS_SLOT_DATA_CATEGORY_KEY])) {
            return null;
        }

        return $cmsSlotData[static::CMS_SLOT_DATA_CATEGORY_KEY];
    }

    protected function getIsCategoryMeetConditions(int $idCategory, array $conditionData): bool
    {
        if (!isset($conditionData[static::CONDITIONS_DATA_KEY_CATEGORY_IDS])) {
            return false;
        }

        $conditionCategoryIds = $conditionData[static::CONDITIONS_DATA_KEY_CATEGORY_IDS];

        return in_array($idCategory, $conditionCategoryIds);
    }
}
