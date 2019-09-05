<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Persistence;

use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Orm\Zed\Touch\Persistence\SpyTouchSearchQuery;
use Orm\Zed\Touch\Persistence\SpyTouchStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Touch\TouchConfig getConfig()
 * @method \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Touch\Persistence\TouchEntityManagerInterface getEntityManager()
 */
class TouchPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    public function createTouchQuery()
    {
        return SpyTouchQuery::create();
    }

    /**
     * @return \Orm\Zed\Touch\Persistence\SpyTouchSearchQuery
     */
    public function createTouchSearchQuery()
    {
        return SpyTouchSearchQuery::create();
    }

    /**
     * @return \Orm\Zed\Touch\Persistence\SpyTouchStorageQuery
     */
    public function createTouchStorageQuery()
    {
        return SpyTouchStorageQuery::create();
    }
}
