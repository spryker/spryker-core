<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Plugin\GlueBackendApiApplication;

use Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RouterPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Symfony\Component\Routing\RouterInterface;

/**
 * @method \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationFactory getFactory()
 */
class CustomRouteRouterPlugin extends AbstractPlugin implements RouterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Routes API request based on the provided `\Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface` plugins.
     *
     * @api
     *
     * @return \Symfony\Component\Routing\RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->getFactory()->createRouter();
    }
}
