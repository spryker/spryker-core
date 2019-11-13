<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockCategoryConnector\Resolver;

use Generated\Shared\Transfer\CmsBlockTransfer;

class CategoryCmsSlotBlockConditionResolver implements CategoryCmsSlotBlockConditionResolverInterface
{
    /**
     * @uses \Spryker\Zed\CmsSlotBlockCategoryGui\Communication\Form\CategorySlotBlockConditionForm::FIELD_ALL
     */
    protected const CONDITIONS_DATA_KEY_ALL = 'all';

    /**
     * @uses \Spryker\Zed\CmsSlotBlockCategoryGui\Communication\Form\CategorySlotBlockConditionForm::FIELD_CATEGORY_IDS
     */
    protected const CONDITIONS_DATA_KEY_CATEGORY_IDS = 'categoryIds';

    /**
     * @uses \Spryker\Shared\CmsSlotBlockCategoryConnector\CmsSlotBlockCategoryConnectorConfig::CONDITION_KEY
     */
    protected const CONDITION_KEY = 'category';

    protected const SLOT_DATA_KEY_ID_CATEGORY = 'idCategory';

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return bool
     */
    public function isSlotBlockConditionApplicable(CmsBlockTransfer $cmsBlockTransfer): bool
    {
        return isset($cmsBlockTransfer->getCmsSlotBlockConditions()[static::CONDITION_KEY]);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param array $cmsSlotParams
     *
     * @return bool
     */
    public function isCmsBlockVisibleInSlot(CmsBlockTransfer $cmsBlockTransfer, array $cmsSlotParams): bool
    {
        $conditionData = $cmsBlockTransfer->getCmsSlotBlockConditions()[static::CONDITION_KEY];

        if ($this->getIsConditionDataAllowsAll($conditionData)) {
            return true;
        }

        $idCategory = $this->getIdCategory($cmsSlotParams);

        if (!$idCategory) {
            return false;
        }

        if ($this->getIsConditionDataAllowsIdCategory($idCategory, $conditionData)) {
            return true;
        }

        return false;
    }

    /**
     * @param array $conditionData
     *
     * @return bool
     */
    protected function getIsConditionDataAllowsAll(array $conditionData): bool
    {
        if (!isset($conditionData[static::CONDITIONS_DATA_KEY_ALL])) {
            return false;
        }

        if (!$conditionData[static::CONDITIONS_DATA_KEY_ALL]) {
            return false;
        }

        return true;
    }

    /**
     * @param array $cmsSlotParams
     *
     * @return int|null
     */
    protected function getIdCategory(array $cmsSlotParams): ?int
    {
        return (int)$cmsSlotParams[static::SLOT_DATA_KEY_ID_CATEGORY] ?? null;
    }

    /**
     * @param int $idCategory
     * @param array $conditionData
     *
     * @return bool
     */
    protected function getIsConditionDataAllowsIdCategory(int $idCategory, array $conditionData): bool
    {
        if (!isset($conditionData[static::CONDITIONS_DATA_KEY_CATEGORY_IDS])) {
            return false;
        }

        return in_array($idCategory, $conditionData[static::CONDITIONS_DATA_KEY_CATEGORY_IDS]);
    }
}
