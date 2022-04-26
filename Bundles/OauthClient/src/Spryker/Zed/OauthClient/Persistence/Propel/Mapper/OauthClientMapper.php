<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient\Persistence\Propel\Mapper;

use DateTime;
use Generated\Shared\Transfer\AccessTokenCacheTransfer;
use Orm\Zed\OauthClient\Persistence\SpyOauthClientAccessTokenCache;

class OauthClientMapper
{
    /**
     * @param \Orm\Zed\OauthClient\Persistence\SpyOauthClientAccessTokenCache $oauthClientAccessTokenCacheEntity
     * @param \Generated\Shared\Transfer\AccessTokenCacheTransfer $accessTokenCacheTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenCacheTransfer
     */
    public function mapOauthClientAccessTokenCacheEntityToAccessTokenCacheTransfer(
        SpyOauthClientAccessTokenCache $oauthClientAccessTokenCacheEntity,
        AccessTokenCacheTransfer $accessTokenCacheTransfer
    ): AccessTokenCacheTransfer {
        $accessTokenCacheTransfer->setAccessToken($oauthClientAccessTokenCacheEntity->getAccessToken());

        if ($oauthClientAccessTokenCacheEntity->getExpiresAt() instanceof DateTime) {
            $accessTokenCacheTransfer->setExpiresAt(
                (string)($oauthClientAccessTokenCacheEntity->getExpiresAt()->getTimestamp()),
            );
        }

        return $accessTokenCacheTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AccessTokenCacheTransfer $accessTokenCacheTransfer
     * @param \Orm\Zed\OauthClient\Persistence\SpyOauthClientAccessTokenCache $oauthClientAccessTokenCacheEntity
     *
     * @return \Orm\Zed\OauthClient\Persistence\SpyOauthClientAccessTokenCache
     */
    public function mapAccessTokenCacheTransferToOauthClientAccessTokenCacheEntity(
        AccessTokenCacheTransfer $accessTokenCacheTransfer,
        SpyOauthClientAccessTokenCache $oauthClientAccessTokenCacheEntity
    ): SpyOauthClientAccessTokenCache {
        return $oauthClientAccessTokenCacheEntity->fromArray($accessTokenCacheTransfer->modifiedToArray());
    }
}
