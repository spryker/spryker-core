<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockCategoryConnector\Resolver;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\CmsSlotParamsTransfer;

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
     * @param \Generated\Shared\Transfer\CmsSlotParamsTransfer $cmsSlotParamsTransfer
     *
     * @return bool
     */
    public function isCmsBlockVisibleInSlot(
        CmsBlockTransfer $cmsBlockTransfer,
        CmsSlotParamsTransfer $cmsSlotParamsTransfer
    ): bool {
        $conditionData = $cmsBlockTransfer->getCmsSlotBlockConditions()[static::CONDITION_KEY];

        if ($conditionData[static::CONDITIONS_DATA_KEY_ALL]) {
            return true;
        }

        if (!$cmsSlotParamsTransfer->getIdCategory()) {
            return false;
        }

        if ($this->getIsConditionSatisfiedByIdCategory($cmsSlotParamsTransfer->getIdCategory(), $conditionData)) {
            return true;
        }

        return false;
    }

    /**
     * @param int $idCategory
     * @param array $conditionData
     *
     * @return bool
     */
    protected function getIsConditionSatisfiedByIdCategory(int $idCategory, array $conditionData): bool
    {
        return in_array($idCategory, $conditionData[static::CONDITIONS_DATA_KEY_CATEGORY_IDS]);
    }
}
