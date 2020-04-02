<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthExtension\Dependency\Plugin;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;

interface OauthRefreshTokenSaverPluginInterface
{
    /**
     * @api
     *
     * Specification:
     * - Revoke the refresh token.
     *
     * @param \League\OAuth2\Server\Entities\RefreshTokenEntityInterface $refreshTokenEntity
     *
     * @return void
     */
    public function saveRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void;
}
