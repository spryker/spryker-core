<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Persistence;

use Orm\Zed\OauthRevoke\Persistence\SpyOauthRefreshTokenQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\OauthRevoke\Persistence\Propel\Mapper\OauthRefreshTokenMapper;

/**
 * @method \Spryker\Zed\OauthRevoke\OauthRevokeConfig getConfig()
 * @method \Spryker\Zed\OauthRevoke\Persistence\OauthRevokeEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\OauthRevoke\Persistence\OauthRevokeRepositoryInterface getRepository()
 */
class OauthRevokePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\OauthRevoke\Persistence\Propel\Mapper\OauthRefreshTokenMapper
     */
    public function createOauthRefreshTokenMapper(): OauthRefreshTokenMapper
    {
        return new OauthRefreshTokenMapper();
    }

    /**
     * @return \Orm\Zed\OauthRevoke\Persistence\SpyOauthRefreshTokenQuery
     */
    public function createRefreshTokenQuery(): SpyOauthRefreshTokenQuery
    {
        return SpyOauthRefreshTokenQuery::create();
    }
}
