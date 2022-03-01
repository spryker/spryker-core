<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueStorefrontApiApplication;

use Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RouterPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Symfony\Component\Routing\RouterInterface;

/**
 * @method \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationFactory getFactory()
 */
class CustomRouteRouterPlugin extends AbstractPlugin implements RouterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Routes API request based on the provided `\Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RouteProviderPluginInterface` plugins.
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
