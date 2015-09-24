<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\Writer\Search;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouchSearchQuery;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\KeyValue\TouchUpdaterSet;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;

class TouchUpdater implements TouchUpdaterInterface
{

    /**
     * @param TouchUpdaterSet $touchUpdaterSet
     * @param $idLocale
     */
    public function updateMulti(TouchUpdaterSet $touchUpdaterSet, $idLocale)
    {
        foreach ($touchUpdaterSet->getData() as $key => $data) {
            $query = SpyTouchSearchQuery::create();
            $query->filterByFkTouch($data[self::TOUCH_EXPORTER_ID]);
            $query->filterByFkLocale($idLocale);
            $entity = $query->findOneOrCreate();
            $entity->setKey($key);
            $entity->save();
        }
    }

    /**
     * @param int $id
     * @param LocaleTransfer $locale
     *
     * @return SpyTouchSearch
     */
    public function getKeyById($id, LocaleTransfer $locale){
        $query = SpyTouchSearchQuery::create();
        $query->filterByFkTouch($id);
        $query->filterByFkLocale($locale->getIdLocale());
        return $query->findOne();
    }

}
