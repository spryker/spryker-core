<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient\Persistence;

use Generated\Shared\Transfer\AccessTokenCacheTransfer;
use Orm\Zed\OauthClient\Persistence\SpyOauthClientAccessTokenCache;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\OauthClient\Persistence\OauthClientPersistenceFactory getFactory()
 */
class OauthClientEntityManager extends AbstractEntityManager implements OauthClientEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AccessTokenCacheTransfer $accessTokenCacheTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenCacheTransfer
     */
    public function saveAccessTokenCache(
        AccessTokenCacheTransfer $accessTokenCacheTransfer
    ): AccessTokenCacheTransfer {
        $oauthClientMapper = $this->getFactory()->createOauthClientMapper();

        $oauthClientAccessTokenCacheEntity = $this->getFactory()->createSpyOauthClientAccessTokenCacheQuery()
            ->findOneByCacheKey((string)$accessTokenCacheTransfer->getCacheKey());

        $oauthClientAccessTokenCacheEntity = $oauthClientMapper->mapAccessTokenCacheTransferToOauthClientAccessTokenCacheEntity(
            $accessTokenCacheTransfer,
            $oauthClientAccessTokenCacheEntity ?? new SpyOauthClientAccessTokenCache(),
        );

        $oauthClientAccessTokenCacheEntity->save();

        return $oauthClientMapper->mapOauthClientAccessTokenCacheEntityToAccessTokenCacheTransfer(
            $oauthClientAccessTokenCacheEntity,
            $accessTokenCacheTransfer,
        );
    }

    /**
     * @param string $cacheKey
     *
     * @return void
     */
    public function deleteAccessTokenCacheEntityByCacheKey(string $cacheKey): void
    {
        $this->getFactory()->createSpyOauthClientAccessTokenCacheQuery()
            ->filterByCacheKey($cacheKey)
            ->delete();
    }
}
