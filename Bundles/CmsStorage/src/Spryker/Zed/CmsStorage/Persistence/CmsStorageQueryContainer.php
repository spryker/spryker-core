<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Persistence;

use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsVersionTableMap;
use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CmsStorage\Persistence\CmsStoragePersistenceFactory getFactory()
 */
class CmsStorageQueryContainer extends AbstractQueryContainer implements CmsStorageQueryContainerInterface
{
    /**
     * @api
     *
     * @param array $localeNames
     *
     * @return \Orm\Zed\Locale\Persistence\SpyLocaleQuery
     */
    public function queryLocalesWithLocaleNames(array $localeNames)
    {
        return $this->getFactory()
            ->getLocaleQueryContainer()
            ->queryLocales()
            ->filterByLocaleName_In($localeNames);
    }

    /**
     * @api
     *
     * @param array $cmsPageIds
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryCmsPageVersionByIds(array $cmsPageIds)
    {
        return $this->getFactory()
            ->getCmsQueryContainer()
            ->queryPages()
            ->filterByIdCmsPage_In($cmsPageIds)
            ->joinWithSpyUrl()
            ->joinWith('SpyUrl.SpyLocale')
            ->joinWithSpyCmsVersion()
            ->where(sprintf('%s = (%s)', SpyCmsVersionTableMap::COL_VERSION, $this->getMaxVersionSubQuery()))
            ->joinWithSpyCmsPageStore(Criteria::LEFT_JOIN)
            ->useSpyCmsPageStoreQuery()
                ->joinWithSpyStore()
            ->endUse();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $cmsPageIds
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPageQuery
     */
    public function queryCmsPageByIds(array $cmsPageIds): SpyCmsPageQuery
    {
        return $this->getFactory()
            ->getCmsQueryContainer()
            ->queryPages()
            ->filterByIdCmsPage_In($cmsPageIds);
    }

    /**
     * @api
     *
     * @param array $cmsPageIds
     *
     * @return \Orm\Zed\CmsStorage\Persistence\SpyCmsPageStorageQuery
     */
    public function queryCmsPageStorageEntities(array $cmsPageIds)
    {
        return $this->getFactory()
            ->createSpyCmsStorageQuery()
            ->filterByFkCmsPage_In($cmsPageIds);
    }

    /**
     * @return string
     */
    protected function getMaxVersionSubQuery()
    {
        $maxVersionQuery = $this->getFactory()
            ->getCmsQueryContainer()
            ->queryAllCmsVersions()
            ->addSelfSelectColumns()
            ->clearSelectColumns()
            ->withColumn(sprintf('MAX(%s)', SpyCmsVersionTableMap::COL_VERSION))
            ->where(sprintf('%s = %s', SpyCmsVersionTableMap::COL_FK_CMS_PAGE, SpyCmsPageTableMap::COL_ID_CMS_PAGE));

        $queryParams = [];
        $queryString = $maxVersionQuery->createSelectSql($queryParams);

        return $queryString;
    }
}
