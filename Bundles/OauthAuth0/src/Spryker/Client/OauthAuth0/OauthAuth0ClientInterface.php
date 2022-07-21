<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthAuth0;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;

interface OauthAuth0ClientInterface
{
    /**
     * Specification:
     * - Retrieves an access token from Auth0 IDP.
     * - Returns `AccessTokenResponseTransfer::isSuccessful = true` in case of successful token retrieval.
     * - Returns `AccessTokenResponseTransfer::isSuccessful = false` in case of failure.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function getAccessToken(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenResponseTransfer;
}
