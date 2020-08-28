<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthExtension\Dependency\Plugin;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenPersistencePluginInterface } instead.
 */
interface OauthRefreshTokenSaverPluginInterface
{
    /**
     * Specification:
     * - Saves refresh token.
     *
     * @api
     *
     * @param \League\OAuth2\Server\Entities\RefreshTokenEntityInterface $refreshTokenEntity
     *
     * @return void
     */
    public function saveRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void;
}
