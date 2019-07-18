<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Uuid\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Uuid\Persistence\Propel\Builder\QueryBuilder;
use Spryker\Zed\Uuid\Persistence\Propel\Builder\QueryBuilderInterface;

/**
 * @method \Spryker\Zed\Uuid\Persistence\UuidEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Uuid\UuidConfig getConfig()
 * @method \Spryker\Zed\Uuid\Persistence\UuidRepositoryInterface getRepository()
 */
class UuidPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\Uuid\Persistence\Propel\Builder\QueryBuilderInterface
     */
    public function createQueryBuilder(): QueryBuilderInterface
    {
        return new QueryBuilder();
    }
}
