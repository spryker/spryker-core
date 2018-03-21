<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Persistence;

use Orm\Zed\Permission\Persistence\SpyPermissionQuery;
use Orm\Zed\Quote\Persistence\SpyQuoteQuery;
use Orm\Zed\SharedCart\Persistence\SpyQuotePermissionGroupQuery;
use Orm\Zed\SharedCart\Persistence\SpyQuotePermissionGroupToPermissionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\SharedCart\SharedCartConfig getConfig()
 */
class SharedCartPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Permission\Persistence\SpyPermissionQuery
     */
    public function createPermissionQuery()
    {
        return SpyPermissionQuery::create();
    }

    /**
     * @return \Orm\Zed\Quote\Persistence\SpyQuoteQuery
     */
    public function createQuoteQuery()
    {
        return SpyQuoteQuery::create();
    }

    /**
     * @return \Orm\Zed\SharedCart\Persistence\SpyQuotePermissionGroupQuery
     */
    public function createQuotePermissionGroupQuery()
    {
        return SpyQuotePermissionGroupQuery::create();
    }

    /**
     * @return \Orm\Zed\SharedCart\Persistence\SpyQuotePermissionGroupToPermissionQuery
     */
    public function createQuotePermissionGroupToPermissionQuery()
    {
        return SpyQuotePermissionGroupToPermissionQuery::create();
    }
}
