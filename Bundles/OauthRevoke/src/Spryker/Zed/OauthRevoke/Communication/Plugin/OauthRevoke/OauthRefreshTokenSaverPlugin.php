<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthRevoke\Communication\Plugin\OauthRevoke;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthExtension\Dependency\Plugin\OauthRefreshTokenSaverPluginInterface;

/**
 * @method \Spryker\Zed\OauthRevoke\Business\OauthRevokeFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthRevoke\OauthRevokeConfig getConfig()
 */
class OauthRefreshTokenSaverPlugin extends AbstractPlugin implements OauthRefreshTokenSaverPluginInterface
{
    /**
     * @api
     *
     * @inheritDoc
     */
    public function saveRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
    {
//        (RefreshTokenEntityInterface $refreshTokenEntity)

        $this->getFacade()->saveRefreshToken($refreshTokenEntity);
    }
}
