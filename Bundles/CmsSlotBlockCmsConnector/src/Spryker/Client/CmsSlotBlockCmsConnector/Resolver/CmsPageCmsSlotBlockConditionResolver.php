<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockCmsConnector\Resolver;

use Generated\Shared\Transfer\CmsSlotBlockTransfer;
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
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     *
     * @return bool
     */
    public function isSlotBlockConditionApplicable(CmsSlotBlockTransfer $cmsSlotBlockTransfer): bool
    {
        return isset($cmsSlotBlockTransfer->getConditions()[static::CONDITION_KEY]);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer $cmsSlotBlockTransfer
     * @param \Generated\Shared\Transfer\CmsSlotParamsTransfer $cmsSlotParamsTransfer
     *
     * @return bool
     */
    public function isCmsBlockVisibleInSlot(
        CmsSlotBlockTransfer $cmsSlotBlockTransfer,
        CmsSlotParamsTransfer $cmsSlotParamsTransfer
    ): bool {
        $conditionData = $cmsSlotBlockTransfer->getConditions()[static::CONDITION_KEY];

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
