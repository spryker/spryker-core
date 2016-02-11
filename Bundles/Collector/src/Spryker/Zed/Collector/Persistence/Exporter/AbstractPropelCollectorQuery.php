<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Persistence\Exporter;

use Orm\Zed\Touch\Persistence\SpyTouchQuery;

abstract class AbstractPropelCollectorQuery extends AbstractCollectorQuery
{

    /**
     * @var \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    protected $touchQuery;

    /**
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function getTouchQuery()
    {
        return $this->touchQuery;
    }

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $touchQuery
     *
     * @return void
     */
    public function setTouchQuery(SpyTouchQuery $touchQuery)
    {
        $this->touchQuery = $touchQuery;
    }

}
