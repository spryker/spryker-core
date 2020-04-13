<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business;

use Generated\Shared\Transfer\OauthAccessTokenValidationRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenValidationResponseTransfer;
use Generated\Shared\Transfer\OauthClientTransfer;
use Generated\Shared\Transfer\OauthRequestTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Generated\Shared\Transfer\OauthScopeTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer;
use Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer;

/**
 * @method \Spryker\Zed\Oauth\Business\OauthBusinessFactory getFactory()
 */
interface OauthFacadeInterface
{
    /**
     * Specification:
     *  - Process token request
     *  - Returns new access token when user provider return valid user
     *  - Executes scope plugins
     *  - Executes user plugins
     *  - Saves issues access token in database for auditing
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
     *  - Validates access JWT token against signature and token
     *
     * @api
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
     *  - Creates new scope in database
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeTransfer $oauthScopeTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer
     */
    public function saveScope(OauthScopeTransfer $oauthScopeTransfer): OauthScopeTransfer;

    /**
     * Specification:
     * - Creates new client in database
     *
     * @api
     *
     * @deprecated Will be removed in the next major.
     *
     * @param \Generated\Shared\Transfer\OauthClientTransfer $oauthClientTransfer
     *
     * @return \Generated\Shared\Transfer\OauthClientTransfer
     */
    public function saveClient(OauthClientTransfer $oauthClientTransfer): OauthClientTransfer;

    /**
     * Specification:
     * - Retrieves a oauth scope using the identifier within the provided transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthScopeTransfer $oauthScopeTransfer
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer|null
     */
    public function findScopeByIdentifier(OauthScopeTransfer $oauthScopeTransfer): ?OauthScopeTransfer;

    /**
     * Specification:
     * - Retrieves a oauth client using the identifier within the provided transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthClientTransfer $oauthClientTransfer
     *
     * @return \Generated\Shared\Transfer\OauthClientTransfer|null
     */
    public function findClientByIdentifier(OauthClientTransfer $oauthClientTransfer): ?OauthClientTransfer;

    /**
     * Specification:
     * - Retrieves a oauth scopes using the identifiers.
     *
     * @api
     *
     * @param string[] $customerScopes
     *
     * @return \Generated\Shared\Transfer\OauthScopeTransfer[]
     */
    public function getScopesByIdentifiers(array $customerScopes): array;

    /**
     * Specification:
     * - Installs oauth client data.
     *
     * @api
     *
     * @return void
     */
    public function installOauthClient(): void;

    /**
     * Specification:
     * - Revokes refresh token by provided identifier and customer reference.
     * - Requires `refreshToken` and `customerReference` to be set on `RevokeRefreshTokenRequestTransfer` taken as parameter.
     * - Decrypts the `refreshToken`.
     * - Looks up the persisted refresh token record by the `identifier` and `customerReference`.
     * - Revokes refresh token found.
     * - Returns `RevokeRefreshTokenResponseTransfer.isSuccessful = true` on success.
     * - Returns `RevokeRefreshTokenResponseTransfer.isSuccessful = false` when refresh token cannot be decrypted.
     * - Returns `RevokeRefreshTokenResponseTransfer.isSuccessful = false` when refresh token cannot be found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer
     */
    public function revokeRefreshToken(RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer): RevokeRefreshTokenResponseTransfer;

    /**
     * Specification:
     * - Revokes all refresh tokens by provided customer reference.
     * - Requires `RevokeRefreshTokenRequestTransfer.customerReference`.
     * - Looks up all refresh tokens by the `customerReference`.
     * - Revokes each refresh token.
     * - Returns `RevokeRefreshTokenResponseTransfer.isSuccessful = true` on success.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RevokeRefreshTokenResponseTransfer
     */
    public function revokeAllRefreshTokens(RevokeRefreshTokenRequestTransfer $revokeRefreshTokenRequestTransfer): RevokeRefreshTokenResponseTransfer;

    /**
     * Specification:
     *  - Deletes refresh tokens where expires_at column less than NOW() + OauthConfig::getRefreshTokenRetentionInterval().
     *
     * @api
     *
     * @return int|null
     */
    public function deleteExpiredRefreshTokens(): ?int;
}
