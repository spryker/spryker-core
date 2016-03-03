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
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $connection
     *
     * @return
     */
    public function updateMulti(TouchUpdaterSet $touchUpdaterSet, $idLocale, ConnectionInterface $connection = null);

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet $touchUpdaterSet
     * @param int $idLocale
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $connection
     *
     * @return void
     */
    public function deleteMulti(TouchUpdaterSet $touchUpdaterSet, $idLocale, ConnectionInterface $connection = null);

    /**
     * @return string
     */
    public function getTouchKeyColumnName();

}
