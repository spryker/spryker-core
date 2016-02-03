<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Orm\Zed\User\Persistence\SpyUserQuery;

/**
 * @method \Spryker\Zed\User\UserConfig getConfig()
 * @method \Spryker\Zed\User\Persistence\UserQueryContainer getQueryContainer()
 */
class UserPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function createUserQuery()
    {
        return new SpyUserQuery();
    }

}
