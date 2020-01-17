<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Orm\Zed\Oauth\Persistence\SpyOauthRefreshToken;

class OauthRefreshTokenMapper
{
    /**
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     * @param \Orm\Zed\Oauth\Persistence\SpyOauthRefreshToken $oauthRefreshTokenEntity
     *
     * @return \Orm\Zed\Oauth\Persistence\SpyOauthRefreshToken
     */
    public function mapOauthRefreshTokenTransferToOauthRefreshTokenEntity(
        OauthRefreshTokenTransfer $oauthRefreshTokenTransfer,
        SpyOauthRefreshToken $oauthRefreshTokenEntity
    ): SpyOauthRefreshToken {
        $oauthRefreshTokenEntity->setIdentifier($oauthRefreshTokenTransfer->getIdentifier());
        $oauthRefreshTokenEntity->setExpiresAt($oauthRefreshTokenTransfer->getExpiresAt());
        $oauthRefreshTokenEntity->setUserIdentifier($oauthRefreshTokenTransfer->getUserIdentifier());
        $oauthRefreshTokenEntity->setFkOauthClient($oauthRefreshTokenTransfer->getIdOauthClient());
        $oauthRefreshTokenEntity->setScopes(json_encode($oauthRefreshTokenTransfer->getScopes()));

        return $oauthRefreshTokenEntity;
    }

    /**
     * @param \Orm\Zed\Oauth\Persistence\SpyOauthRefreshToken $oauthRefreshTokenEntity
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenTransfer
     */
    public function mapOauthRefreshTokenEntityToMapOauthRefreshTokenTransfer(
        SpyOauthRefreshToken $oauthRefreshTokenEntity,
        OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
    ): OauthRefreshTokenTransfer {
        $oauthRefreshTokenTransfer->fromArray($oauthRefreshTokenEntity->toArray(), true);

        return $oauthRefreshTokenTransfer;
    }
}
