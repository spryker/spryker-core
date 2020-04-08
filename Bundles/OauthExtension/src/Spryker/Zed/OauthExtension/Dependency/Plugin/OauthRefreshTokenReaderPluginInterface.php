<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;

interface OauthRefreshTokenReaderPluginInterface
{
    /**
     * Specification:
     * - Checks if provided criteria is acceptable by this plugin.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return bool
     */
    public function isApplicable(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): bool;

    /**
     * Specification:
     * - Finds refresh token by provided criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OauthRefreshTokenTransfer|null
     */
    public function findRefreshToken(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): ?OauthRefreshTokenTransfer;
}
