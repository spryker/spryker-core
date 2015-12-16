<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Orm\Zed\User\Persistence\SpyUserQuery;

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
