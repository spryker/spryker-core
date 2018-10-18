<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Persistence;

use Orm\Zed\Oauth\Persistence\SpyOauthAccessTokenQuery;
use Orm\Zed\Oauth\Persistence\SpyOauthClientQuery;
use Orm\Zed\Oauth\Persistence\SpyOauthScopeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Oauth\OauthConfig getConfig()
 */
class OauthPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Oauth\Persistence\SpyOauthClientQuery
     */
    public function createOauthClientQuery(): SpyOauthClientQuery
    {
        return SpyOauthClientQuery::create();
    }

    /**
     * @return \Orm\Zed\Oauth\Persistence\SpyOauthAccessTokenQuery
     */
    public function createAccessTokenQuery(): SpyOauthAccessTokenQuery
    {
        return SpyOauthAccessTokenQuery::create();
    }

    /**
     * @return \Orm\Zed\Oauth\Persistence\SpyOauthScopeQuery
     */
    public function createScopeQuery(): SpyOauthScopeQuery
    {
        return SpyOauthScopeQuery::create();
    }
}
