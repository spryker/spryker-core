<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\Search;

use Orm\Zed\Touch\Persistence\Map\SpyTouchSearchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchSearchQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Collector\Business\Exporter\Writer\AbstractTouchUpdater;
use Spryker\Zed\Collector\CollectorConfig;

class TouchUpdater extends AbstractTouchUpdater
{
    /**
     * @var string
     */
    protected $touchKeyTableName = SpyTouchSearchTableMap::TABLE_NAME;

    /**
     * @var string
     */
    protected $touchKeyIdColumnName = SpyTouchSearchTableMap::COL_ID_TOUCH_SEARCH;

    /**
     * @var string
     */
    protected $touchKeyColumnName = CollectorConfig::COLLECTOR_SEARCH_KEY;

    /**
     * @param string $key
     * @param int $idLocale
     * @param int $idStore
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchSearch
     */
    protected function findOrCreateTouchKeyEntity($key, $idLocale, $idStore)
    {
        $spyTouchSearchQuery = SpyTouchSearchQuery::create()
            ->filterByKey($key)
            ->filterByFkStore($idStore)
            ->filterByFkLocale($idLocale);

        return $spyTouchSearchQuery->findOneOrCreate();
    }

    /**
     * @param array<string> $keys
     * @param int $idLocale
     *
     * @return void
     */
    public function deleteTouchKeyEntities($keys, $idLocale)
    {
        SpyTouchSearchQuery::create()
            ->filterByKey($keys, Criteria::IN)
            ->filterByFkLocale($idLocale)
            ->delete();
    }
}
