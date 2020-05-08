<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League\Repositories;

use ArrayObject;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface as LeagueRefreshTokenRepositoryInterface;

interface RefreshTokenRepositoryInterface extends LeagueRefreshTokenRepositoryInterface
{
    /**
     * Revoke all refresh tokens.
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\OauthRefreshTokenTransfer[] $oauthRefreshTokenTransfers
     *
     * @return void
     */
    public function revokeAllRefreshTokens(ArrayObject $oauthRefreshTokenTransfers): void;
}
