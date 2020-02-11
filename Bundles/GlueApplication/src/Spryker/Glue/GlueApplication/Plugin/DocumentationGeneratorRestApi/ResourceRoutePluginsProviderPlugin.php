<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\DocumentationGeneratorRestApi;

use Spryker\Glue\DocumentationGeneratorRestApiExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class ResourceRoutePluginsProviderPlugin extends AbstractPlugin implements ResourceRoutePluginsProviderPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Returns plugins that are defined for GlueApplication on project level
     *
     * @api
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface[]
     */
    public function getResourceRoutePlugins(): array
    {
        return $this->getFactory()->getResourceRoutePlugins();
    }
}
