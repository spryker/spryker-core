<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Persistence\Exporter;

use Orm\Zed\Touch\Persistence\SpyTouchQuery;

abstract class AbstractPropelCollectorQuery extends AbstractCollectorQuery
{

    /**
     * @var SpyTouchQuery
     */
    protected $touchQuery;

    /**
     * @return SpyTouchQuery
     */
    public function getTouchQuery()
    {
        return $this->touchQuery;
    }

    /**
     * @param SpyTouchQuery $touchQuery
     */
    public function setTouchQuery(SpyTouchQuery $touchQuery)
    {
        $this->touchQuery = $touchQuery;
    }

}
