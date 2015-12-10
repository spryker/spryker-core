<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Collector\Search;

use SprykerFeature\Zed\Collector\Business\Plugin\AbstractPdoCollectorPlugin;
use SprykerFeature\Zed\Collector\CollectorConfig;

abstract class AbstractSearchPdoCollector extends AbstractPdoCollectorPlugin
{

    /**
     * @return void
     */
    protected function ensureCollectorColumnsAreSelected()
    {
        $sql = sprintf($this->criteriaBuilder->getSqlTemplate(),
            CollectorConfig::COLLECTOR_TOUCH_ID,
            CollectorConfig::COLLECTOR_RESOURCE_ID,
            CollectorConfig::COLLECTOR_SEARCH_KEY_ID
        );

        $this->criteriaBuilder->sql($sql);
    }

}
