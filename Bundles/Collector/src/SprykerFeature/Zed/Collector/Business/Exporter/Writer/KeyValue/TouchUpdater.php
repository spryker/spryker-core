<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\Writer\KeyValue;


use SprykerEngine\Zed\Touch\Persistence\Propel\Base\SpyTouchStorageQuery;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouchStorage;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;

class TouchUpdater implements TouchUpdaterInterface
{
    public function updateMulti(array $keysToUpdate)
    {
        foreach ($keysToUpdate as $key => $data) {
            //$touchStorageEntity = new SpyTouchStorage();
            $query = SpyTouchStorageQuery::create();
            $query->filterByFkTouch($data['touch_id']);
            $query->filterByFkLocale($data['touch_updater_locale_id']);
            $entity = $query->findOneOrCreate();
            $entity->setKey($key);
            $entity->save();
        }
        
    }
}
