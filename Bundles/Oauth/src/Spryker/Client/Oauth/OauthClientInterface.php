<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Oauth;

use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;

/**
 * @method \Spryker\Client\Oauth\OauthFactory getFactory()
 */
interface OauthClientInterface
{
    /**
     * @api
     *
     * Specification:
     *  - Process access tokens request, makes RPC ZED call to retrieve new access token
     *
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function processAccessTokenRequest(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer;

    /**
     * @api
     *
     * Specification:
     *  - Validates JWT token, checks if fingerprint is valid using public key, does not go to ZED.
     *
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function validateAccessToken(OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer): OauthAccessTokenValidationResponseTransfer;
}
