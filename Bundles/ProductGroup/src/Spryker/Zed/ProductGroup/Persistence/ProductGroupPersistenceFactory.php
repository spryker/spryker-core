<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Persistence;

use Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroupQuery;
use Orm\Zed\ProductGroup\Persistence\SpyProductGroupQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ProductGroup\ProductGroupConfig getConfig()
 * @method \Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainer getQueryContainer()
 */
class ProductGroupPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductGroupQuery
     */
    public function createProductGroupQuery()
    {
        return SpyProductGroupQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroupQuery
     */
    public function createProductAbstractGroupQuery()
    {
        return SpyProductAbstractGroupQuery::create();
    }
}
