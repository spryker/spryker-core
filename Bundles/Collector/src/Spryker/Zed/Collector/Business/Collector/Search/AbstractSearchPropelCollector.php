<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Collector\Search;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Map\SpyTouchSearchTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Collector\Business\Collector\AbstractPropelCollector;
use Spryker\Zed\Collector\CollectorConfig;

abstract class AbstractSearchPropelCollector extends AbstractPropelCollector
{
    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $touchQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    protected function prepareCollectorScope(SpyTouchQuery $touchQuery, LocaleTransfer $locale)
    {
        $touchQuery->addJoin(
            [SpyTouchTableMap::COL_ID_TOUCH, SpyTouchSearchTableMap::COL_FK_LOCALE],
            [SpyTouchSearchTableMap::COL_FK_TOUCH, $locale->getIdLocale()],
            Criteria::LEFT_JOIN
        );

        $touchQuery->withColumn(SpyTouchSearchTableMap::COL_ID_TOUCH_SEARCH, CollectorConfig::COLLECTOR_SEARCH_KEY);

        parent::prepareCollectorScope($touchQuery, $locale);
    }
}
