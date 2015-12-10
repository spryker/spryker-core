<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\Search;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchSearch;
use Orm\Zed\Touch\Persistence\SpyTouchSearchQuery;
use Spryker\Zed\Collector\Business\Exporter\Writer\KeyValue\TouchUpdaterSet;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Collector\Business\Exporter\AbstractPropelCollectorPlugin;

class TouchUpdater implements TouchUpdaterInterface
{

    const COLLECTOR_KEY_ID = AbstractPropelCollectorPlugin::COLLECTOR_SEARCH_KEY_ID;

    /**
     * @param TouchUpdaterSet $touchUpdaterSet
     * @param int $idLocale
     * @param ConnectionInterface $connection
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function updateMulti(TouchUpdaterSet $touchUpdaterSet, $idLocale, ConnectionInterface $connection = null)
    {
        foreach ($touchUpdaterSet->getData() as $key => $touchData) {
            $query = SpyTouchSearchQuery::create();
            $query->filterByFkTouch($touchData[self::COLLECTOR_TOUCH_ID]);
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
     * @return SpyTouchSearch
     */
    public function getKeyById($idTouch, LocaleTransfer $locale)
    {
        $query = SpyTouchSearchQuery::create();
        $query->filterByFkTouch($idTouch);
        $query->filterByFkLocale($locale->getIdLocale());

        return $query->findOne();
    }

}
