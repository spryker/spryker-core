<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAuth0\Communication\Plugin\OauthClient;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\AccessTokenResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthClientExtension\Dependency\Plugin\OauthAccessTokenProviderPluginInterface;

/**
 * @method \Spryker\Zed\OauthAuth0\Business\OauthAuth0FacadeInterface getFacade()
 * @method \Spryker\Zed\OauthAuth0\OauthAuth0Config getConfig()
 */
class Auth0OauthAccessTokenProviderPlugin extends AbstractPlugin implements OauthAccessTokenProviderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(AccessTokenRequestTransfer $accessTokenRequestTransfer): bool
    {
        return $accessTokenRequestTransfer->getProviderName() === $this->getConfig()->getProviderName();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenResponseTransfer
     */
    public function getAccessToken(
        AccessTokenRequestTransfer $accessTokenRequestTransfer
    ): AccessTokenResponseTransfer {
        return $this->getFacade()->getAccessToken($accessTokenRequestTransfer);
    }
}
