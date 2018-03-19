<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Persistence;

use Orm\Zed\SharedCart\Persistence\SpyQuotePermissionGroupToPermissionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\SharedCart\Persistence\SharedCartQueryContainerInterface getQueryContainer()
 */
class SharedCartPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SharedCart\Persistence\SpyQuotePermissionGroupToPermissionQuery
     */
    public function createQuotePermissionGroupToPermissionQuery()
    {
        return SpyQuotePermissionGroupToPermissionQuery::create();
    }
}
