<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Persistence;

use Orm\Zed\Auth\Persistence\SpyResetPasswordQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Auth\AuthConfig getConfig()
 * @method \Spryker\Zed\Auth\Persistence\AuthQueryContainerInterface getQueryContainer()
 */
class AuthPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Auth\Persistence\SpyResetPasswordQuery
     */
    public function createResetPasswordQuery()
    {
        return SpyResetPasswordQuery::create();
    }
}
