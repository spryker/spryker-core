<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockCmsConnector\Resolver;

class CmsPageCmsSlotBlockConditionResolver implements CmsPageCmsSlotBlockConditionResolverInterface
{
    // TODO: add @uses
    protected const CONDITIONS_DATA_KEY_ALL = 'all';

    // TODO: add @uses
    protected const CONDITIONS_DATA_KEY_CMS_PAGE_IDS = 'cmsPageIds';

    protected const CMS_SLOT_DATA_CMS_PAGE_KEY = 'idCmsPage';
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

        $idCmsPage = $this->getIdCmsPage($cmsSlotData);

        if (!$idCmsPage) {
            return false;
        }

        if ($this->getIsCmsPageMeetConditions($idCmsPage, $conditionData)) {
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
        if (!isset($conditionData[static::CONDITIONS_DATA_KEY_ALL])) {
            return false;
        }

        if (!$conditionData[static::CONDITIONS_DATA_KEY_ALL]) {
            return false;
        }

        return true;
    }
    /**
     * @param array $cmsSlotData
     *
     * @return int|null
     */
    protected function getIdCmsPage(array $cmsSlotData): ?int
    {
        return $cmsSlotData[static::CMS_SLOT_DATA_CMS_PAGE_KEY] ?? null;
    }
    /**
     * @param int $idCmsPage
     * @param array $conditionData
     *
     * @return bool
     */
    protected function getIsCmsPageMeetConditions(int $idCmsPage, array $conditionData): bool
    {
        if (!isset($conditionData[static::CONDITIONS_DATA_KEY_CMS_PAGE_IDS])) {
            return false;
        }

        $conditionCmsPageIds = $conditionData[static::CONDITIONS_DATA_KEY_CMS_PAGE_IDS];

        return in_array($idCmsPage, $conditionCmsPageIds);
    }
}
