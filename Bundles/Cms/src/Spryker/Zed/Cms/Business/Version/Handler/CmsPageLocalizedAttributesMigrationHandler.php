<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version\Handler;

use Orm\Zed\Cms\Persistence\Map\SpyCmsPageLocalizedAttributesTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsPageLocalizedAttributesMigrationHandler implements MigrationHandlerInterface
{

    /**
     * @var CmsToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param CmsToLocaleInterface $localeFacade
     * @param CmsQueryContainerInterface $queryContainer
     */
    public function __construct(CmsToLocaleInterface $localeFacade, CmsQueryContainerInterface $queryContainer)
    {
        $this->localeFacade = $localeFacade;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param array $originData
     * @param array $targetData
     *
     * @return void
     */
    public function handle(array $originData, array $targetData)
    {
        foreach ($targetData[SpyCmsPageLocalizedAttributesTableMap::TABLE_NAME] as $localeName => $cmsPageLocalizedAttributes) {
            $localeTransfer = $this->localeFacade->getLocale($localeName);
            $cmsLocalizedAttributeEntity = $this->queryContainer
                ->queryCmsPageLocalizedAttributesByFkPageAndFkLocale(
                    $originData[SpyCmsPageTableMap::COL_ID_CMS_PAGE],
                    $localeTransfer->getIdLocale()
                )
                ->findOneOrCreate();

            $cmsLocalizedAttributeEntity->setName($cmsPageLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_NAME]);
            $cmsLocalizedAttributeEntity->setMetaTitle($cmsPageLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_META_TITLE]);
            $cmsLocalizedAttributeEntity->setMetaKeywords($cmsPageLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_META_KEYWORDS]);
            $cmsLocalizedAttributeEntity->setMetaDescription($cmsPageLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_META_DESCRIPTION]);

            $cmsLocalizedAttributeEntity->save();
        }
    }
}
