<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAuth0\Business;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;

interface OauthAuth0FacadeInterface
{
    /**
     * Specification:
     * - Retrieves an access token from an OauthAuth0 client by AccessTokenRequestTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function getAccessToken(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenResponseTransfer;

    /**
     * Specification:
     * - Expands `AccessTokenRequest.cacheKeySeed` with credentials hash.
     * - Makes no change if `AccessTokenRequest.providerName` is the same as `OauthAuth0Config::PROVIDER_NAME`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenRequestTransfer
     */
    public function expandAccessTokenRequest(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenRequestTransfer;
}
