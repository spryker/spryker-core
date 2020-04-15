<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthExtension\Dependency\Plugin;

use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;

interface OauthExpiredRefreshTokenRemoverPluginInterface
{
    /**
     * Specification:
     * - Removes expired refresh tokens by provided criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return int
     */
    public function deleteExpiredRefreshTokens(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): int;
}
