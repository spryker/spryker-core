<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Communication\Plugin\Oauth;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenSaverPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\OauthRevoke\Communication\Plugin\Oauth\OauthRefreshTokenPersistencePlugin} instead.
 *
 * @method \Spryker\Zed\OauthRevoke\Business\OauthRevokeFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthRevoke\OauthRevokeConfig getConfig()
 */
class OauthRefreshTokenSaverPlugin extends AbstractPlugin implements OauthRefreshTokenSaverPluginInterface
{
    /**
     * {@inheritDoc}
     * - Persists the new refresh token to permanent storage.
     *
     * @api
     *
     * @param \League\OAuth2\Server\Entities\RefreshTokenEntityInterface $refreshTokenEntity
     *
     * @return void
     */
    public function saveRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
        $this->getFacade()->saveRefreshToken($refreshTokenEntity);
    }
}
