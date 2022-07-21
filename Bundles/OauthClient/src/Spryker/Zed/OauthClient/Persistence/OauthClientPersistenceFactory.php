<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient\Persistence;

use Orm\Zed\OauthClient\Persistence\SpyOauthClientAccessTokenCacheQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\OauthClient\Persistence\Propel\Mapper\OauthClientMapper;

/**
 * @method \Spryker\Zed\OauthClient\OauthClientConfig getConfig()
 * @method \Spryker\Zed\OauthClient\Persistence\OauthClientEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\OauthClient\Persistence\OauthClientRepositoryInterface getRepository()
 */
class OauthClientPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\OauthClient\Persistence\SpyOauthClientAccessTokenCacheQuery
     */
    public function createSpyOauthClientAccessTokenCacheQuery(): SpyOauthClientAccessTokenCacheQuery
    {
        return SpyOauthClientAccessTokenCacheQuery::create();
    }

    /**
     * @return \Spryker\Zed\OauthClient\Persistence\Propel\Mapper\OauthClientMapper
     */
    public function createOauthClientMapper(): OauthClientMapper
    {
        return new OauthClientMapper();
    }
}
