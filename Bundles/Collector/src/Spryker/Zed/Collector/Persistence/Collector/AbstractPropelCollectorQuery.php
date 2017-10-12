<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Persistence\Collector;

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
