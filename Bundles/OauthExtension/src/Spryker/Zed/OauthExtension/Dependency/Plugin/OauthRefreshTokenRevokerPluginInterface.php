<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthExtension\Dependency\Plugin;

interface OauthRefreshTokenRevokerPluginInterface
{
    /**
     * @api
     *
     * Specification:
     * - Revoke the refresh token.
     *
     * @param string $tokenId
     *
     * @return void
     */
    public function revokeRefreshToken(string $tokenId): void;
}
