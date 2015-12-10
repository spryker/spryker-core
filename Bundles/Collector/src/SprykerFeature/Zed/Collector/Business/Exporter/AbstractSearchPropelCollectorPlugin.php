<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Map\SpyTouchSearchTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Propel\Runtime\ActiveQuery\Criteria;

abstract class AbstractSearchPropelCollectorPlugin extends AbstractPropelCollectorPlugin
{

    /**
     * @param SpyTouchQuery $touchQuery
     * @param LocaleTransfer $locale
     *
     * @return void
     */
    protected function prepareCollectorScope(SpyTouchQuery $touchQuery, LocaleTransfer $locale)
    {
        $touchQuery->addJoin(
            SpyTouchTableMap::COL_ID_TOUCH,
            SpyTouchSearchTableMap::COL_FK_TOUCH,
            Criteria::LEFT_JOIN
        );

        $touchQuery->withColumn(SpyTouchSearchTableMap::COL_ID_TOUCH_SEARCH, AbstractPdoCollectorPlugin::COLLECTOR_SEARCH_KEY_ID);

        parent::prepareCollectorScope($touchQuery, $locale);
    }

}
