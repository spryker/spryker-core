<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthExpiredRefreshTokenRemoverPluginInterface;

/**
 * @method \Spryker\Zed\OauthRevoke\Business\OauthRevokeFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthRevoke\OauthRevokeConfig getConfig()
 */
class OauthExpiredRefreshTokenRemoverPlugin extends AbstractPlugin implements OauthExpiredRefreshTokenRemoverPluginInterface
{
    /**
     * {@inheritDoc}
     * - Removes expired refresh tokens by provided criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer
     *
     * @return int
     */
    public function deleteExpiredRefreshTokens(OauthTokenCriteriaFilterTransfer $oauthTokenCriteriaFilterTransfer): int
    {
        return $this->getFacade()->deleteExpiredRefreshTokens($oauthTokenCriteriaFilterTransfer);
    }
}
