<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthenticationOauth\Business;

use Generated\Shared\Transfer\GlueAuthenticationRequestTransfer;
use Generated\Shared\Transfer\GlueAuthenticationResponseTransfer;

interface AuthenticationOauthFacadeInterface
{
    /**
     * Specification:
     * - Makes a call to {@link \Spryker\Zed\Oauth\Business\OauthFacadeInterface::processAccessTokenRequest()}.
     * - Builds `GlueAuthenticationResponseTransfer` with proper access token if the credentials are valid.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueAuthenticationResponseTransfer
     */
    public function authenticate(GlueAuthenticationRequestTransfer $glueAuthenticationRequestTransfer): GlueAuthenticationResponseTransfer;
}
