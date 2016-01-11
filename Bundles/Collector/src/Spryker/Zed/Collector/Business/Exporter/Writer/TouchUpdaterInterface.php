<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer;

use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\KeyValue\TouchUpdaterSet;
use Spryker\Zed\Collector\CollectorConfig;

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
     * @param TouchUpdaterSet $touchUpdaterSet
     * @param int $idLocale
     * @param ConnectionInterface|null $connection
     *
     * @return void
     */
    public function deleteMulti(TouchUpdaterSet $touchUpdaterSet, $idLocale, ConnectionInterface $connection = null);

    /**
     * @return string
     */
    public function getTouchKeyColumnName();

}
