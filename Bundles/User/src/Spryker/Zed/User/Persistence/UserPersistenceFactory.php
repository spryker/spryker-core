<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Persistence;

use Orm\Zed\User\Persistence\SpyUserQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\User\Persistence\Propel\Mapper\UserMapper;

/**
 * @method \Spryker\Zed\User\UserConfig getConfig()
 * @method \Spryker\Zed\User\Persistence\UserQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\User\Persistence\UserRepositoryInterface getRepository()
 */
class UserPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function createUserQuery()
    {
        return SpyUserQuery::create();
    }

    /**
     * @return \Spryker\Zed\User\Persistence\Propel\Mapper\UserMapper
     */
    public function createUserMapper(): UserMapper
    {
        return new UserMapper();
    }
}
