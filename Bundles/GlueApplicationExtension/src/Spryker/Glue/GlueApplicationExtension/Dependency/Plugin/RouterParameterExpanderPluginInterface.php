<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

interface RouterParameterExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands resource configuration with additional parameters.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $resourceRoutePlugin
     * @param array<mixed> $resourceConfiguration
     *
     * @return array<mixed>
     */
    public function expandResourceConfiguration(ResourceRoutePluginInterface $resourceRoutePlugin, array $resourceConfiguration): array;

    /**
     * Specification:
     * - Expands route parameters with additional parameters.
     *
     * @api
     *
     * @param array<mixed> $resourceConfiguration
     * @param array<mixed> $routeParams
     *
     * @return array<mixed>
     */
    public function expandRouteParameters(array $resourceConfiguration, array $routeParams): array;
}
