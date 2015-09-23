<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\Writer\KeyValue;


use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Touch\Persistence\Propel\Base\SpyTouchStorageQuery;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;

class TouchUpdater implements TouchUpdaterInterface
{


    /**
     * @param TouchUpdaterSet $touchUpdaterSet
     * @param $idLocale
     */
    public function updateMulti(TouchUpdaterSet $touchUpdaterSet, $idLocale)
    {
        //TODO: make one raw query for whole set
        foreach ($touchUpdaterSet->getData() as $key => $data) {
            $query = SpyTouchStorageQuery::create();
            $query->filterByFkTouch($data[self::TOUCH_EXPORTER_ID]);
            $query->filterByFkLocale($idLocale);
            $entity = $query->findOneOrCreate();
            $entity->setKey($key);
            $entity->save();
        }
    }

    public function getKeyById($id, LocaleTransfer $locale){
        $query = SpyTouchStorageQuery::create();
        $query->filterByFkTouch($id);
        $query->filterByFkLocale($locale->getIdLocale());
        return $query->findOne();
    }
}
