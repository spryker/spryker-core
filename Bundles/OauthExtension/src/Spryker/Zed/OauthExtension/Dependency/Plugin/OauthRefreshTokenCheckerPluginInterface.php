<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthExtension\Dependency\Plugin;

interface OauthRefreshTokenCheckerPluginInterface
{
    /**
     * Specification:
     * - Checks if refresh token identifier is acceptable by this plugin.
     *
     * @api
     *
     * @param string $tokenId
     *
     * @return bool
     */
    public function isApplicable(string $tokenId): bool;

    /**
     * Specification:
     * - Checks if refresh token has been revoked.
     *
     * @api
     *
     * @param string $tokenId
     *
     * @return bool
     */
    public function isRefreshTokenRevoked(string $tokenId): bool;
}
