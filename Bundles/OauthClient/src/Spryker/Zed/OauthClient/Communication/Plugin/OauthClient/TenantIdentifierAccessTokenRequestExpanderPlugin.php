<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthClient\Communication\Plugin\OauthClient;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\OauthClientExtension\Dependency\Plugin\AccessTokenRequestExpanderPluginInterface;

/**
 * @method \Spryker\Zed\OauthClient\OauthClientConfig getConfig()
 * @method \Spryker\Zed\OauthClient\Business\OauthClientFacadeInterface getFacade()
 */
class TenantIdentifierAccessTokenRequestExpanderPlugin extends AbstractPlugin implements AccessTokenRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Locates a tenant identifier if it has been provided.
     * - Expands `AccessTokenRequest.accessTokenRequestOptions` by including the located tenant identifier.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenRequestTransfer
     */
    public function expand(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenRequestTransfer
    {
        return $this->getFacade()->expandAccessTokenRequestWithTenantIdentifier($accessTokenRequestTransfer);
    }
}
