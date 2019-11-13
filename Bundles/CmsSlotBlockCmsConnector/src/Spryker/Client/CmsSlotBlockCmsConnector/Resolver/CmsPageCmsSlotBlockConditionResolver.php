<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockCmsConnector\Resolver;

use Generated\Shared\Transfer\CmsBlockTransfer;

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

    protected const SLOT_DATA_KEY_ID_CMS_PAGE = 'idCmsPage';

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

        $idCmsPage = $this->getIdCmsPage($cmsSlotParams);

        if (!$idCmsPage) {
            return false;
        }

        if ($this->getIsConditionDataAllowsIdCmsPage($idCmsPage, $conditionData)) {
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
    protected function getIdCmsPage(array $cmsSlotParams): ?int
    {
        return (int)$cmsSlotParams[static::SLOT_DATA_KEY_ID_CMS_PAGE] ?? null;
    }

    /**
     * @param int $idCmsPage
     * @param array $conditionData
     *
     * @return bool
     */
    protected function getIsConditionDataAllowsIdCmsPage(int $idCmsPage, array $conditionData): bool
    {
        if (!isset($conditionData[static::CONDITIONS_DATA_KEY_PAGE_IDS])) {
            return false;
        }

        return in_array($idCmsPage, $conditionData[static::CONDITIONS_DATA_KEY_PAGE_IDS]);
    }
}
