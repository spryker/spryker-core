<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

abstract class AbstractKeyValuePdoCollectorPlugin extends AbstractPdoCollectorPlugin
{

    /**
     * @return void
     */
    protected function ensureCollectorColumnsAreSelected()
    {
        $sql = sprintf($this->criteriaBuilder->getSqlTemplate(),
            static::COLLECTOR_TOUCH_ID,
            static::COLLECTOR_RESOURCE_ID,
            static::COLLECTOR_STORAGE_KEY_ID
        );

        $this->criteriaBuilder->sql($sql);
    }

}
