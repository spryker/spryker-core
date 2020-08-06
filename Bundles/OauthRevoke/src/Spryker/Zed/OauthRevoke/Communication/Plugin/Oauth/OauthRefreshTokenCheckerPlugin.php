<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenCheckerPluginInterface;

/**
 * @method \Spryker\Zed\OauthRevoke\Business\OauthRevokeFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthRevoke\OauthRevokeConfig getConfig()
 */
class OauthRefreshTokenCheckerPlugin extends AbstractPlugin implements OauthRefreshTokenCheckerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if refresh token identifier is not empty.
     *
     * @api
     *
     * @param string $tokenId
     *
     * @return bool
     */
    public function isApplicable(string $tokenId): bool
    {
        return (bool)$tokenId;
    }

    /**
     * {@inheritDoc}
     * - Checks if refresh token has been revoked.
     * - Returns true, if refresh token has been revoked.
     * - Returns false, if refresh token has not been revoked.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $oauthRefreshTokenTransfer
     *
     * @return bool
     */
    public function isRefreshTokenRevoked(OauthRefreshTokenTransfer $oauthRefreshTokenTransfer): bool
    {
        return $this->getFacade()->isRefreshTokenRevoked($oauthRefreshTokenTransfer);
    }
}
