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
use Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer;

/**
 * @method \Spryker\Client\Oauth\OauthFactory getFactory()
 */
interface OauthClientInterface
{
    /**
     * Specification:
     *  - Process access tokens request, makes RPC Zed call to retrieve new access token
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthRequestTransfer $oauthRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthResponseTransfer
     */
    public function processAccessTokenRequest(OauthRequestTransfer $oauthRequestTransfer): OauthResponseTransfer;

    /**
     * Specification:
     *  - Validates JWT token, checks if fingerprint is valid using public key, does not go to Zed.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Client\Oauth\OauthClient::validateOauthAccessToken()} instead.
     *
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer
     */
    public function validateAccessToken(
        OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer
    ): OauthAccessTokenValidationResponseTransfer;

    /**
     * Specification:
     * - Validates JWT token.
     * - Checks if fingerprint is valid using public key.
     * - Does not go to Zed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer
     */
    public function validateOauthAccessToken(
        OauthAccessTokenValidationRequestTransfer $authAccessTokenValidationRequestTransfer
    ): OauthAccessTokenValidationResponseTransfer;

    /**
     * Specification:
     * - Revokes refresh token by identifier and customer reference.
     * - Makes Zed request.
     *
     * @api
     *
     * @param string $refreshTokenIdentifier
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer
     */
    public function revokeRefreshToken(string $refreshTokenIdentifier, string $customerReference): RevokeRefreshTokenResponseTransfer;

    /**
     * Specification:
     * - Revokes all refresh tokens for the given customer reference.
     * - Makes Zed request.
     *
     * @api
     *
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer
     */
    public function revokeAllRefreshTokens(string $customerReference): RevokeRefreshTokenResponseTransfer;
}
