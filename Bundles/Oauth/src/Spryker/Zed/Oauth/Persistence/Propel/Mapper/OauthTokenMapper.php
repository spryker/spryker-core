<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\OauthAccessTokenTransfer;
use Orm\Zed\Oauth\Persistence\SpyOauthAccessToken;

class OauthTokenMapper
{
    /**
     * @param \Orm\Zed\Oauth\Persistence\SpyOauthAccessToken $oauthAccessTokenEntity
     * @param \Generated\Shared\Transfer\OauthAccessTokenTransfer $oauthAccessTokenTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenTransfer
     */
    public function mapOauthAccessTokenEntityToOauthAccessTokenTransfer(
        SpyOauthAccessToken $oauthAccessTokenEntity,
        OauthAccessTokenTransfer $oauthAccessTokenTransfer
    ): OauthAccessTokenTransfer {
        $oauthAccessTokenTransfer->setAccessTokenIdentifier($oauthAccessTokenEntity->getIdentifier());

        /** @phpstan-var string $expirityDate */
        $expirityDate = $oauthAccessTokenEntity->getExpirityDate();
        $oauthAccessTokenTransfer->setExpiresAt($expirityDate);

        return $oauthAccessTokenTransfer;
    }
}
