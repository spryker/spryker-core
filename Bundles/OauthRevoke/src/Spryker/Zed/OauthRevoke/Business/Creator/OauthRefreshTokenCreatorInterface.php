<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Business\Creator;

use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;

interface OauthRefreshTokenCreatorInterface
{
    /**
     * @deprecated Use {@link \Spryker\Zed\OauthRevoke\Business\Creator::saveRefreshTokenFromTransfer()} instead.
     *
     * @param \League\OAuth2\Server\Entities\RefreshTokenEntityInterface $refreshTokenEntity
     *
     * @return void
     */
    public function saveRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void;

    /**
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     *
     * @return void
     */
    public function saveRefreshTokenFromTransfer(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): void;
}
