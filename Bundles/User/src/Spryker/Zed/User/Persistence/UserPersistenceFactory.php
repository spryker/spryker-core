<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
