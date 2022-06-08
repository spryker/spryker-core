<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClientExtension\Dependency\Plugin;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;

/**
 * Use this plugin to add a new provider to receive access tokens.
 */
interface OauthAccessTokenProviderPluginInterface
{
    /**
     * Specification:
     * - Checks if this provider plugin is applicable to execute.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(AccessTokenRequestTransfer $accessTokenRequestTransfer): bool;

    /**
     * Specification:
     * - Retrieves an access token from an access token provider by AccessTokenRequestTransfer.
     * - Returns `AccessTokenResponseTransfer::isSuccessful = true` in case of successful token retrieval.
     * - Returns `AccessTokenResponseTransfer::isSuccessful = false` in case of failure.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function getAccessToken(
        AccessTokenRequestTransfer $accessTokenRequestTransfer
    ): AccessTokenResponseTransfer;
}
