<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Business\Collector\Search;

use Spryker\Zed\Collector\Business\Plugin\AbstractPdoCollectorPlugin;
use Spryker\Zed\Collector\CollectorConfig;

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
            CollectorConfig::COLLECTOR_SEARCH_KEY
        );

        $this->criteriaBuilder->sql($sql);
    }

}
