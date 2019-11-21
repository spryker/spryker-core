<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockCmsConnector\Resolver;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\CmsSlotParamsTransfer;

class CmsPageCmsSlotBlockConditionResolver implements CmsPageCmsSlotBlockConditionResolverInterface
{
    /**
     * @uses \Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\CmsPageBlockConditionForm::FIELD_ALL
     */
    protected const CONDITIONS_DATA_KEY_ALL = 'all';

    /**
     * @uses \Spryker\Zed\CmsSlotBlockCmsGui\Communication\Form\CmsPageBlockConditionForm::FIELD_PAGE_IDS
     */
    protected const CONDITIONS_DATA_KEY_PAGE_IDS = 'pageIds';

    /**
     * @uses \Spryker\Shared\CmsSlotBlockCmsConnector\CmsSlotBlockCmsConnectorConfig::CONDITION_KEY
     */
    protected const CONDITION_KEY = 'cms_page';

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
        if (!$cmsSlotParamsTransfer->getIdCmsPage()) {
            return false;
        }

        if ($this->getIsConditionSatisfiedByIdCmsPage($cmsSlotParamsTransfer->getIdCmsPage(), $conditionData)) {
            return true;
        }

        return false;
    }

    /**
     * @param int $idCmsPage
     * @param array $conditionData
     *
     * @return bool
     */
    protected function getIsConditionSatisfiedByIdCmsPage(int $idCmsPage, array $conditionData): bool
    {
        return in_array($idCmsPage, $conditionData[static::CONDITIONS_DATA_KEY_PAGE_IDS]);
    }
}
