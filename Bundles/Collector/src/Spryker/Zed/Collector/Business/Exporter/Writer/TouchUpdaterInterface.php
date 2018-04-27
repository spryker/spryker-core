<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer;

use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet;

interface TouchUpdaterInterface
{
    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet $touchUpdaterSet
     * @param int $idLocale
     * @param int $idStore
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $connection
     *
     * @return
     */
    public function bulkUpdate(TouchUpdaterSet $touchUpdaterSet, $idLocale, $idStore, ?ConnectionInterface $connection = null);

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet $touchUpdaterSet
     * @param int $idLocale
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $connection
     *
     * @return void
     */
    public function bulkDelete(TouchUpdaterSet $touchUpdaterSet, $idLocale, ?ConnectionInterface $connection = null);

    /**
     * @return string
     */
    public function getTouchKeyColumnName();

    /**
     * @param string[] $keys
     * @param int $idLocale
     *
     * @return void
     */
    public function deleteTouchKeyEntities($keys, $idLocale);
}
