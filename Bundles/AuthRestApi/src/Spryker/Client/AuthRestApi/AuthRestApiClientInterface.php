<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AuthRestApi;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;

interface AuthRestApiClientInterface
{
    /**
     * Specification:
     * - Creates token request.
     * - Executes post auth plugins.
     * - Returns OauthResponseTransfer with new access token if user provider returned valid user.
     * - Returns OauthResponseTransfer with error if user provider did not return valid user.
     * - Makes call to Zed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function createAccessToken(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer;
}
