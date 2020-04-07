<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenRevokerPluginInterface;

/**
 * @method \Spryker\Zed\OauthRevoke\Business\OauthRevokeFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthRevoke\OauthRevokeConfig getConfig()
 */
class OauthRefreshTokenRevokerPlugin extends AbstractPlugin implements OauthRefreshTokenRevokerPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $tokenId
     *
     * @return void
     */
    public function revokeRefreshToken(string $tokenId): void
    {
        $oauthRefreshTokenTransfer = (new OauthRefreshTokenTransfer())
            ->setIdentifier($tokenId);

        $this->getFacade()->revokeRefreshToken($oauthRefreshTokenTransfer);
    }
}
