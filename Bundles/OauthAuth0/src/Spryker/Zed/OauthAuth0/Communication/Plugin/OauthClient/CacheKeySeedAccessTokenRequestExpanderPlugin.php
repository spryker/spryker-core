<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthAuth0\Communication\Plugin\OauthClient;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthClientExtension\Dependency\Plugin\AccessTokenRequestExpanderPluginInterface;

/**
 * @method \Spryker\Zed\OauthAuth0\Business\OauthAuth0FacadeInterface getFacade()
 * @method \Spryker\Zed\OauthAuth0\OauthAuth0Config getConfig()
 */
class CacheKeySeedAccessTokenRequestExpanderPlugin extends AbstractPlugin implements AccessTokenRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `AccessTokenRequest.cacheKeySeed` with credentials hash.
     * - Makes no change if `AccessTokenRequest.providerName` is the same as `OauthAuth0Config::PROVIDER_NAME`.
     * - Generates a cache key seed to ignore the cached access token if the Auth0 credential changes.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenRequestTransfer
     */
    public function expand(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenRequestTransfer
    {
        return $this->getFacade()->expandAccessTokenRequest($accessTokenRequestTransfer);
    }
}
