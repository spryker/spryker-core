<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver;

use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;

class CmsPageKeysToIdsConditionsResolver implements ConditionsResolverInterface
{
    protected const KEY_CMS_PAGE_IDS = 'pageIds';

    /**
     * @var int[]
     */
    protected $cmsPageIdsBuffer = [];

    /**
     * @param string $conditionValue
     * @param array $conditionsArray
     *
     * @return array
     */
    public function getConditions(string $conditionValue, array $conditionsArray = []): array
    {
        $cmsPageKeys = explode(',', $conditionValue);
        $conditionsArray[static::KEY_CMS_PAGE_IDS] = $this->getCmsPageIdsByKeys($cmsPageKeys);

        return $conditionsArray;
    }

    /**
     * @param string[] $cmsPageKeys
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int[]
     */
    protected function getCmsPageIdsByKeys(array $cmsPageKeys): array
    {
        $cmsPageIds = [];

        foreach ($cmsPageKeys as $key => $cmsPageKey) {
            if (!isset($this->cmsPageIdsBuffer[$cmsPageKey])) {
                continue;
            }

            $cmsPageIds[] = $this->cmsPageIdsBuffer[$cmsPageKey];
            unset($cmsPageKeys[$key]);
        }

        if (!$cmsPageKeys) {
            return $cmsPageIds;
        }

        $cmsPageEntities = SpyCmsPageQuery::create()
            ->filterByPageKey_In($cmsPageKeys)
            ->find();

        if ($cmsPageEntities->count() < 1) {
            throw new EntityNotFoundException(
                sprintf(
                    'Could not find CMS Page IDs by keys "%s".',
                    implode(',', $cmsPageKeys)
                )
            );
        }

        foreach ($cmsPageEntities as $cmsPage) {
            $idCmsPage = $cmsPage->getIdCmsPage();
            $this->cmsPageIdsBuffer[$cmsPage->getPageKey()] = $idCmsPage;
            $cmsPageIds[] = $idCmsPage;
        }

        return $cmsPageIds;
    }
}
