<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Persistence;

use Orm\Zed\Oauth\Persistence\SpyOauthAccessTokenQuery;
use Orm\Zed\Oauth\Persistence\SpyOauthClientQuery;
use Orm\Zed\Oauth\Persistence\SpyOauthRefreshTokenQuery;
use Orm\Zed\Oauth\Persistence\SpyOauthScopeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Oauth\Persistence\Propel\Mapper\OauthAccessTokenMapper;
use Spryker\Zed\Oauth\Persistence\Propel\Mapper\OauthRefreshTokenMapper;

/**
 * @method \Spryker\Zed\Oauth\OauthConfig getConfig()
 * @method \Spryker\Zed\Oauth\Persistence\OauthEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Oauth\Persistence\OauthRepositoryInterface getRepository()
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
     * @return \Orm\Zed\Oauth\Persistence\SpyOauthRefreshTokenQuery
     */
    public function createRefreshTokenQuery(): SpyOauthRefreshTokenQuery
    {
        return SpyOauthRefreshTokenQuery::create();
    }

    /**
     * @return \Orm\Zed\Oauth\Persistence\SpyOauthScopeQuery
     */
    public function createScopeQuery(): SpyOauthScopeQuery
    {
        return SpyOauthScopeQuery::create();
    }

    /**
     * @return \Spryker\Zed\Oauth\Persistence\Propel\Mapper\OauthRefreshTokenMapper
     */
    public function createOauthRefreshTokenMapper(): OauthRefreshTokenMapper
    {
        return new OauthRefreshTokenMapper();
    }

    /**
     * @return \Spryker\Zed\Oauth\Persistence\Propel\Mapper\OauthAccessTokenMapper
     */
    public function createOauthAccessTokenMapper(): OauthAccessTokenMapper
    {
        return new OauthAccessTokenMapper();
    }
}
