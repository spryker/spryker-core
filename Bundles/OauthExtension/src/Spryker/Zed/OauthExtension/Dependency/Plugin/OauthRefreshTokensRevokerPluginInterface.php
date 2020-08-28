<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthExtension\Dependency\Plugin;

use ArrayObject;

interface OauthRefreshTokensRevokerPluginInterface
{
    /**
     * Specification:
     * - Revokes all the refresh tokens.
     *
     * @api
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\OauthRefreshTokenTransfer[] $oauthRefreshTokenTransfers
     *
     * @return void
     */
    public function revokeAllRefreshTokens(ArrayObject $oauthRefreshTokenTransfers): void;
}
