<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\Writer\KeyValue;


use SprykerEngine\Zed\Touch\Persistence\Propel\Base\SpyTouchStorageQuery;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;

class TouchUpdater implements TouchUpdaterInterface
{
    const TOUCH_EXPORTER_ID = 'exporter_touch_id';

    /**
     * @param TouchUpdaterSet $touchUpdaterSet
     * @param $locale_id
     */
    public function updateMulti(TouchUpdaterSet $touchUpdaterSet, $locale_id)
    {
        //TODO: make one raw query for whole set
        foreach ($touchUpdaterSet->getData() as $key => $data) {
            $query = SpyTouchStorageQuery::create();
            $query->filterByFkTouch($data[self::TOUCH_EXPORTER_ID]);
            $query->filterByFkLocale($locale_id);
            $entity = $query->findOneOrCreate();
            $entity->setKey($key);
            $entity->save();
        }
    }
}
