<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Persistence\Collector\Propel;

use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsVersionTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchStorageTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class CmsPageCollectorQuery extends AbstractPropelCollectorQuery
{

    /**
     * @return void
     */
    protected function prepareQuery()
    {
        $this->touchQuery->addJoin(
            SpyTouchTableMap::COL_ITEM_ID,
            SpyCmsPageTableMap::COL_ID_CMS_PAGE,
            Criteria::INNER_JOIN
        )
        ->addJoin(
            SpyCmsPageTableMap::COL_ID_CMS_PAGE,
            SpyCmsVersionTableMap::COL_FK_CMS_PAGE,
            Criteria::INNER_JOIN
        )
        ->addJoin(
            SpyCmsPageTableMap::COL_ID_CMS_PAGE,
            SpyUrlTableMap::COL_FK_RESOURCE_PAGE,
            Criteria::INNER_JOIN
        )
        ->addAnd(
            SpyUrlTableMap::COL_FK_LOCALE,
            $this->getLocale()->getIdLocale(),
            Criteria::EQUAL
        );

        $this->touchQuery->withColumn(SpyCmsVersionTableMap::COL_DATA);
        $this->touchQuery->withColumn(sprintf('max(%s)', SpyCmsVersionTableMap::COL_VERSION));
        $this->touchQuery->withColumn(SpyUrlTableMap::COL_URL);

        $this->touchQuery->addGroupByColumn(SpyTouchTableMap::COL_ID_TOUCH);
        $this->touchQuery->addGroupByColumn(SpyTouchTableMap::COL_ITEM_EVENT);
        $this->touchQuery->addGroupByColumn(SpyTouchTableMap::COL_ITEM_TYPE);
        $this->touchQuery->addGroupByColumn(SpyTouchTableMap::COL_ITEM_ID);
        $this->touchQuery->addGroupByColumn(SpyTouchTableMap::COL_TOUCHED);
        $this->touchQuery->addGroupByColumn(SpyUrlTableMap::COL_URL);
    }
}
