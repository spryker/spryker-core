<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthUserConnector\Plugin\GlueBackendApiApplicationAuthorizationConnector;

use Generated\Shared\Transfer\RouteAuthorizationConfigTransfer;
use Spryker\Glue\GlueBackendApiApplicationAuthorizationConnectorExtension\Dependency\Plugin\ProtectedRouteAuthorizationConfigProviderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\OauthUserConnector\OauthUserConnectorFactory getFactory()
 */
class OauthUserScopeProtectedRouteAuthorizationConfigProviderPlugin extends AbstractPlugin implements ProtectedRouteAuthorizationConfigProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Provides Oauth users authorization strategy configuration.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\RouteAuthorizationConfigTransfer
     */
    public function provide(): RouteAuthorizationConfigTransfer
    {
        return $this->getFactory()->createOauthUserConnectorRouteAuthorizationConfigProvider()->provide();
    }
}
