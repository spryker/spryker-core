<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Collector\KeyValue;

use SprykerFeature\Zed\Collector\Business\Plugin\AbstractPdoCollectorPlugin;
use SprykerFeature\Zed\Collector\CollectorConfig;

abstract class AbstractKeyValuePdoCollector extends AbstractPdoCollectorPlugin
{

    /**
     * @return void
     */
    protected function ensureCollectorColumnsAreSelected()
    {
        $sql = sprintf($this->criteriaBuilder->getSqlTemplate(),
            CollectorConfig::COLLECTOR_TOUCH_ID,
            CollectorConfig::COLLECTOR_RESOURCE_ID,
            CollectorConfig::COLLECTOR_STORAGE_KEY
        );

        $this->criteriaBuilder->sql($sql);
    }

}
