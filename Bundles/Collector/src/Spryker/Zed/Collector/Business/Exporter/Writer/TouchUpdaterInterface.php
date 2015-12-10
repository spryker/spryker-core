<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\Writer;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Base\SpyTouchStorage;
use Orm\Zed\Touch\Persistence\SpyTouchSearch;
use Propel\Runtime\Connection\ConnectionInterface;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\KeyValue\TouchUpdaterSet;
use SprykerFeature\Zed\Collector\CollectorConfig;

interface TouchUpdaterInterface
{

    const COLLECTOR_TOUCH_ID = CollectorConfig::COLLECTOR_TOUCH_ID;

    /**
     * @param TouchUpdaterSet $touchUpdaterSet
     * @param int $idLocale
     * @param ConnectionInterface $connection
     *
     * @return
     */
    public function updateMulti(TouchUpdaterSet $touchUpdaterSet, $idLocale, ConnectionInterface $connection = null);

    /**
     * @param int $idTouch
     * @param LocaleTransfer $locale
     *
     * @return SpyTouchStorage|SpyTouchSearch
     */
    public function getKeyById($idTouch, LocaleTransfer $locale);

}
