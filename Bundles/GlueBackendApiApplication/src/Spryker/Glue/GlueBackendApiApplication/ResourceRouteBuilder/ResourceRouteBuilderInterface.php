<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\ResourceRouteBuilder;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;

interface ResourceRouteBuilderInterface
{
    /**
     * Specification:
     * - Builds a set of routes from resource provider plugin.
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resourcePlugin
     *
     * @return array<string, \Symfony\Component\Routing\Route>
     */
    public function buildRoutes(ResourceInterface $resourcePlugin): array;
}
