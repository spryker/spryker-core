<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\Writer\KeyValue;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Base\SpyTouchStorageQuery;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;

class TouchUpdater implements TouchUpdaterInterface
{

    /**
     * @param TouchUpdaterSet $touchUpdaterSet
     * @param int $idLocale
     */
    public function updateMulti(TouchUpdaterSet $touchUpdaterSet, $idLocale)
    {
        //TODO: make one raw query for whole set
        foreach ($touchUpdaterSet->getData() as $key => $touchData) {
            $query = SpyTouchStorageQuery::create();
            $query->filterByFkTouch($touchData[self::TOUCH_EXPORTER_ID]);
            $query->filterByFkLocale($idLocale);
            $entity = $query->findOneOrCreate();
            $entity->setKey($key);
            $entity->save();
        }
    }

    /**
     * @param int $idTouch
     * @param LocaleTransfer $locale
     *
     * @return SpyTouchStorage
     */
    public function getKeyById($idTouch, LocaleTransfer $locale)
    {
        $query = SpyTouchStorageQuery::create();
        $query->filterByFkTouch($idTouch);
        $query->filterByFkLocale($locale->getIdLocale());

        return $query->findOne();
    }

}
