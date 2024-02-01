<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplicationAuthorizationConnectorExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RouteAuthorizationConfigTransfer;

/**
 * Provides authorization configuration for protected path routes.
 */
interface ProtectedRouteAuthorizationConfigProviderPluginInterface
{
    /**
     * Specification:
     * - Provides additional configuration to authorize protected path routes.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\RouteAuthorizationConfigTransfer
     */
    public function provide(): RouteAuthorizationConfigTransfer;
}
