<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthRestApi\Business;

use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;

interface AuthRestApiFacadeInterface
{
    /**
     * Specification:
     * - Creates token request.
     * - Executes post auth plugins.
     * - Sets anonymous customer reference in OauthResponseTransfer before passing to plugins.
     * - Returns OauthResponseTransfer with new access token if user provider returned valid user.
     * - Returns OauthResponseTransfer with error if user provider did not return valid user.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function createAccessToken(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer;
}
