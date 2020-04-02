<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Business;

use ArrayObject;
use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;

interface OauthRevokeFacadeInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenTransfer|null
     */
    public function findOne(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): ?OauthRefreshTokenTransfer;

    public function revokeRefreshToken(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer);

    public function revokeRefreshTokens(ArrayObject $oauthRefreshTokenTransfers);

    public function isRefreshTokenRevoked(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer);

    public function saveRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void;
}
