<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Communication\Plugin\Oauth;

use Generated\Shared\Transfer\OauthRefreshTokenTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenPersistencePluginInterface;

/**
 * @method \Spryker\Zed\OauthRevoke\Business\OauthRevokeFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthRevoke\OauthRevokeConfig getConfig()
 */
class OauthRefreshTokenPersistencePlugin extends AbstractPlugin implements OauthRefreshTokenPersistencePluginInterface
{
    /**
     * {@inheritDoc}
     * - Persists the new refresh token to permanent storage.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OauthRefreshTokenTransfer $refreshTokenTransfer
     *
     * @return void
     */
    public function saveRefreshToken(OauthRefreshTokenTransfer $refreshTokenTransfer): void
    {
        $this->getFacade()->saveRefreshTokenFromTransfer($refreshTokenTransfer);
    }
}
