<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\Writer\KeyValue;

use Orm\Zed\Touch\Persistence\Map\SpyTouchStorageTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchStorage;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\AbstractTouchUpdater;
use SprykerFeature\Zed\Collector\CollectorConfig;

class TouchUpdater extends AbstractTouchUpdater
{

    /**
     * @var string
     */
    protected $touchKeyTableName = SpyTouchStorageTableMap::TABLE_NAME;

    /**
     * @var string
     */
    protected $touchKeyIdColumnName = SpyTouchStorageTableMap::COL_ID_TOUCH_STORAGE;

    /**
     * @var string
     */
    protected $touchKeyColumnName = CollectorConfig::COLLECTOR_STORAGE_KEY;

    /**
     * @return SpyTouchStorage
     */
    protected function createTouchKeyEntity()
    {
        return new SpyTouchStorage();
    }

}
