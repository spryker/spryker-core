<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Collector\Storage;

use Spryker\Zed\Collector\Business\Plugin\AbstractPdoCollectorPlugin;
use Spryker\Zed\Collector\CollectorConfig;

abstract class AbstractStoragePdoCollector extends AbstractPdoCollectorPlugin
{

    /**
     * @return void
     */
    protected function ensureCollectorColumnsAreSelected()
    {
        $sql = sprintf(
            $this->criteriaBuilder->getSqlTemplate(),
            CollectorConfig::COLLECTOR_TOUCH_ID,
            CollectorConfig::COLLECTOR_RESOURCE_ID,
            CollectorConfig::COLLECTOR_STORAGE_KEY
        );

        $this->criteriaBuilder->sql($sql);
    }

}
