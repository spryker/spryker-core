<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Communication\Plugin\OauthRevoke;

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
     *
     * @api
     *
     * @param string $tokenId
     *
     * @return bool
     */
    public function isRefreshTokenRevoked(string $tokenId): bool
    {
        $oauthRefreshTokenTransfer = (new OauthRefreshTokenTransfer())
            ->setIdentifier($tokenId);

        return $this->getFacade()->isRefreshTokenRevoked($oauthRefreshTokenTransfer);
    }
}
