<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Business\Mapper;

use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;

interface OauthRefreshTokenMapperInterface
{
    /**
     * @param \League\OAuth2\Server\Entities\RefreshTokenEntityInterface $refreshTokenEntity
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenTransfer
     */
    public function mapRefreshTokenEntityToOauthRefreshTokenTransfer(
        RefreshTokenEntityInterface $refreshTokenEntity,
        OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
    ): OauthRefreshTokenTransfer;
}
