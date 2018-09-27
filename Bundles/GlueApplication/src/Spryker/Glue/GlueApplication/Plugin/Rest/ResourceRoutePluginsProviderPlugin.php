<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\Rest;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class ResourceRoutePluginsProviderPlugin extends AbstractPlugin implements ResourceRoutePluginsProviderPluginInterface
{
    /**
     * {@inheritdoc}
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
