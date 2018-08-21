<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin;

interface ResourceRoutePluginsProviderPluginInterface
{
    /**
     * Specification:
     *  - Returns an array of GLUE Resource Route plugins
     *
     * @api
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface[]
     */
    public function getResourceRoutePlugins(): array;
}
