<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\Storage;

use Orm\Zed\Touch\Persistence\Map\SpyTouchStorageTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchStorageQuery;
use Spryker\Zed\Collector\Business\Exporter\Writer\AbstractTouchUpdater;
use Spryker\Zed\Collector\CollectorConfig;

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
     * @param string $key
     * @param int $idLocale
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchStorage
     */
    protected function findOrCreateTouchKeyEntity($key, $idLocale)
    {
        return SpyTouchStorageQuery::create()
            ->filterByKey($key)
            ->filterByFkLocale($idLocale)
            ->findOneOrCreate();
    }

    /**
     * @param string $key
     * @param int $idLocale
     *
     * @return void
     */
    public function deleteTouchKeyEntity($key, $idLocale)
    {
        SpyTouchStorageQuery::create()
            ->filterByKey($key)
            ->filterByFkLocale($idLocale)
            ->delete();
    }

}
