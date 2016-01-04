<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Collector\KeyValue;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Map\SpyTouchStorageTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Zed\Collector\Business\Plugin\AbstractPropelCollectorPlugin;
use SprykerFeature\Zed\Collector\CollectorConfig;

abstract class AbstractKeyValuePropelCollector extends AbstractPropelCollectorPlugin
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
            SpyTouchStorageTableMap::COL_FK_TOUCH,
            Criteria::LEFT_JOIN
        );

        $touchQuery->withColumn(SpyTouchStorageTableMap::COL_ID_TOUCH_STORAGE, CollectorConfig::COLLECTOR_STORAGE_KEY);

        parent::prepareCollectorScope($touchQuery, $locale);
    }

}
