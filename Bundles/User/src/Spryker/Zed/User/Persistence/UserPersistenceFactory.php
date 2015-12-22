<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Orm\Zed\User\Persistence\SpyUserQuery;
use Spryker\Zed\User\UserConfig;

/**
 * @method UserConfig getConfig()
 * @method UserQueryContainer getQueryContainer()
 */
class UserPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return SpyUserQuery
     */
    public function createUserQuery()
    {
        return new SpyUserQuery();
    }

}
