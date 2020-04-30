<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OauthRefreshTokenTransfer;

interface OauthRefreshTokenRevokerPluginInterface
{
    /**
     * Specification:
     * - Revokes refresh token.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     *
     * @return void
     */
    public function revokeRefreshToken(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): void;
}
