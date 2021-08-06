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
     * @phpstan-param array<mixed> $resourceConfiguration
     *
     * @phpstan-return array<mixed>
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $resourceRoutePlugin
     * @param array $resourceConfiguration
     *
     * @return mixed
     */
    public function expandResourceConfiguration(ResourceRoutePluginInterface $resourceRoutePlugin, array $resourceConfiguration): array;

    /**
     * Specification:
     * - Expands route parameters with additional parameters.
     *
     * @api
     *
     * @phpstan-param array<mixed> $resourceConfiguration
     * @phpstan-param array<mixed> $routeParams
     *
     * @phpstan-return array<mixed>
     *
     * @param mixed|array $resourceConfiguration
     * @param mixed|array $routeParams
     *
     * @return mixed|array
     */
    public function expandRouteParameters(array $resourceConfiguration, array $routeParams): array;
}
