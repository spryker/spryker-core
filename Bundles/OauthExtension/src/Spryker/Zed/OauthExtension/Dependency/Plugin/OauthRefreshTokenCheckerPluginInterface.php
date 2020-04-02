<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthExtension\Dependency\Plugin;

interface OauthRefreshTokenCheckerPluginInterface
{
    /**
     * @api
     *
     * Specification:
     * - Check if the refresh token has been revoked.
     *
     * @param string $tokenId
     *
     * @return bool
     */
    public function isRefreshTokenRevoked(string $tokenId): bool;
}
