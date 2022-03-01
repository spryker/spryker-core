<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\GlueApplication;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Spryker\Glue\GlueBackendApiApplicationExtension\Dependency\Plugin\RouteMatcherPluginInterface as BackendRouteMatcherPluginInterface;
use Spryker\Glue\GlueStorefrontApiApplicationExtension\Dependency\Plugin\RouteMatcherPluginInterface as StorefrontRouteMatcherPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class ResourceRouteMatcherPlugin extends AbstractPlugin implements BackendRouteMatcherPluginInterface, StorefrontRouteMatcherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Routes API requests using the `ResourceInterface` plugins provided by the API application.
     * - Uses `Resource->getType()` to match the used URL path.
     * - Sets `GlueRequestTransfer.resource` type and id.
     * - Uses {@link \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceFilterPluginInterface} to further filter wired resources.
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
        return $this->getFactory()
            ->createResourceRouter()
            ->matchRequest($glueRequestTransfer, $resourcePlugins);
    }
}
