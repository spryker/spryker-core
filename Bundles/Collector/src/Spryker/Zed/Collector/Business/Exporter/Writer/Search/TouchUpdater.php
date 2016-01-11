<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\Search;

use Orm\Zed\Touch\Persistence\Map\SpyTouchSearchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchSearch;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\Collector\Business\Exporter\Writer\AbstractTouchUpdater;

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
     * @return SpyTouchSearch
     */
    protected function createTouchKeyEntity()
    {
        return new SpyTouchSearch();
    }

}
