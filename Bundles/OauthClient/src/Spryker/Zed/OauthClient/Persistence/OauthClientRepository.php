<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient\Persistence;

use Generated\Shared\Transfer\AccessTokenCacheTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\OauthClient\Persistence\OauthClientPersistenceFactory getFactory()
 */
class OauthClientRepository extends AbstractRepository implements OauthClientRepositoryInterface
{
    /**
     * @param string $cacheKey
     *
     * @return \Generated\Shared\Transfer\AccessTokenCacheTransfer|null
     */
    public function findAccessTokenCacheByCacheKey(string $cacheKey): ?AccessTokenCacheTransfer
    {
        $oauthClientAccessTokenCacheEntity = $this->getFactory()
            ->createSpyOauthClientAccessTokenCacheQuery()
            ->filterByCacheKey($cacheKey)
            ->findOne();

        if (!$oauthClientAccessTokenCacheEntity) {
            return null;
        }

        return $this->getFactory()->createOauthClientMapper()
            ->mapOauthClientAccessTokenCacheEntityToAccessTokenCacheTransfer(
                $oauthClientAccessTokenCacheEntity,
                new AccessTokenCacheTransfer(),
            );
    }
}
