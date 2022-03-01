<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Plugin\GlueBackendApiApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RouteMatcherPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationFactory getFactory()
 */
class CustomRouteRouteMatcherPlugin extends AbstractPlugin implements RouteMatcherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Routes the API request using the custom routes.
     * - Executes stack of {@link \Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RouterPluginInterface} plugins.
     * - Executes stack of {@link \Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RequestResourceFilterPluginInterface} plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface> $resourcePlugins
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface
     */
    public function route(GlueRequestTransfer $glueRequestTransfer, array $resourcePlugins): ResourceInterface
    {
        return $this->getFactory()->createRequestRoutingMatcher()->matchRequest($glueRequestTransfer, $resourcePlugins);
    }
}
