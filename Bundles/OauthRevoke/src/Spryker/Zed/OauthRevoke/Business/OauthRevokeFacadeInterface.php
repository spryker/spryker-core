<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Business;

use ArrayObject;
use Generated\Shared\Transfer\OauthRefreshTokenCollectionTransfer;
use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;

interface OauthRevokeFacadeInterface
{
    /**
     * Specification:
     * - Returns amount of deleted refresh tokens by provided criteria which can be filtered by `identifier`, `customer reference`, `isRevoked` and `expiresAt` fields.
     * - Requires `expiresAt` to be set on `OauthTokenCriteriaFilterTransfer` taken as parameter.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return int
     */
    public function deleteExpiredRefreshTokens(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): int;

    /**
     * Specification:
     * - Returns collection of refresh tokens by provided criteria which can be filtered by `identifier`, `customer reference`, `isRevoked` and `expiresAt` fields.
     * - Returns `OauthRefreshTokenTransfer` if found, NULL otherwise.
     * - Requires `identifier` to be set on `OauthTokenCriteriaFilterTransfer` taken as parameter.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenTransfer|null
     */
    public function findRefreshToken(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): ?OauthRefreshTokenTransfer;

    /**
     * Specification:
     * - Returns collection of refresh tokens by provided criteria which can be filtered by `identifier`, `customer reference`, `isRevoked` and `expiresAt` fields.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenCollectionTransfer
     */
    public function getRefreshTokens(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): OauthRefreshTokenCollectionTransfer;

    /**
     * Specification:
     * - Checks if refresh token isn't revoked yet by provided identifier.
     * - Revokes refresh token by provided identifier.
     * - Requires `identifier` to be set on `OauthRefreshTokenTransfer` taken as parameter.
     * - Looks up the persisted refresh token record by the `identifier`.
     * - Revokes refresh token found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     *
     * @return void
     */
    public function revokeRefreshToken(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): void;

    /**
     * Specification:
     * - Revokes all refresh tokens by provided identifiers.
     * - Requires `identifier` to be set on each `OauthRefreshTokenTransfer` given from `ArrayObject` taken as parameter.
     * - Looks up all refresh tokens by the `identifier` list.
     * - Revokes each refresh token.
     *
     * @api
     *
     * @param \ArrayObject $oauthRefreshTokenTransfers
     *
     * @return void
     */
    public function revokeAllRefreshTokens(ArrayObject $oauthRefreshTokenTransfers): void;

    /**
     * Specification:
     * - Check if the refresh token has been revoked by provided identifier.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     *
     * @return bool
     */
    public function isRefreshTokenRevoked(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): bool;

    /**
     * Specification:
     * - Executes `OauthUserIdentifierFilterPluginInterface` stack of plugins.
     * - Persists the new refresh token to permanent storage.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\OauthRevoke\Business\OauthRevokeFacadeInterface::saveRefreshTokenFromTransfer() } instead.
     *
     * @param \League\OAuth2\Server\Entities\RefreshTokenEntityInterface $refreshTokenEntity
     *
     * @return void
     */
    public function saveRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void;

    /**
     * Specification:
     * - Executes `OauthUserIdentifierFilterPluginInterface` stack of plugins.
     * - Persists the new refresh token to permanent storage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     *
     * @return void
     */
    public function saveRefreshTokenFromTransfer(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): void;
}
