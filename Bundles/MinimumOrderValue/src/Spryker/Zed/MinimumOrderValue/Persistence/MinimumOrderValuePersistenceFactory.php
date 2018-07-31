<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Persistence;

use Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValueQuery;
use Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValueTypeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MinimumOrderValue\Persistence\Propel\Mapper\MinimumOrderValueMapper;
use Spryker\Zed\MinimumOrderValue\Persistence\Propel\Mapper\MinimumOrderValueMapperInterface;

/**
 * @method \Spryker\Zed\MinimumOrderValue\MinimumOrderValueConfig getConfig()
 * @method \Spryker\Zed\MinimumOrderValue\Persistence\MinimumOrderValueRepositoryInterface getRepository()
 */
class MinimumOrderValuePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValueTypeQuery
     */
    public function createMinimumOrderValueTypeQuery(): SpyMinimumOrderValueTypeQuery
    {
        return SpyMinimumOrderValueTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\MinimumOrderValue\Persistence\SpyMinimumOrderValueQuery
     */
    public function createMinimumOrderValueQuery(): SpyMinimumOrderValueQuery
    {
        return SpyMinimumOrderValueQuery::create();
    }

    /**
     * @return \Spryker\Zed\MinimumOrderValue\Persistence\Propel\Mapper\MinimumOrderValueMapperInterface
     */
    public function createMinimumOrderValueMapper(): MinimumOrderValueMapperInterface
    {
        return new MinimumOrderValueMapper();
    }
}
