<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version\Handler;

use Orm\Zed\Cms\Persistence\Map\SpyCmsPageLocalizedAttributesTableMap;
use Spryker\Zed\Cms\Business\Version\Handler\MigrationHandlerInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsPageLocalizedAttributesMigrationHandler implements MigrationHandlerInterface
{

    const SPY_CMS_PAGE_LOCALIZED_ATTRIBUTES_PHP_NAME = 'SpyCmsPageLocalizedAttributess';

    /**
     * @var CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param CmsQueryContainerInterface $queryContainer
     */
    public function __construct(CmsQueryContainerInterface $queryContainer)
    {
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
        foreach ($targetData[static::SPY_CMS_PAGE_LOCALIZED_ATTRIBUTES_PHP_NAME] as $cmsPageLocalizedAttributes) {
            $cmsLocalizedAttributeEntity = $this->queryContainer
                ->queryCmsPageLocalizedAttributesByFkPageAndFkLocale(
                    $cmsPageLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_FK_CMS_PAGE],
                    $cmsPageLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_FK_LOCALE]
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
