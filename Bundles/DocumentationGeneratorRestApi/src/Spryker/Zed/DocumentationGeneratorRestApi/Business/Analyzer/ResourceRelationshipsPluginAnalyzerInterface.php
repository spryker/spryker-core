<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;

interface ResourceRelationshipsPluginAnalyzerInterface
{
    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return array<string, \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface>
     */
    public function getResourceRelationshipsForResourceRoutePlugin(ResourceRoutePluginInterface $plugin): array;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $resourceRoutePlugin
     *
     * @return array<string, \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface>
     */
    public function getNestedResourceRelationshipsForResourceRoutePlugin(ResourceRoutePluginInterface $resourceRoutePlugin): array;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface $resourceRelationshipPlugin
     *
     * @return array<string, \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface>
     */
    public function getResourceRelationshipsForResourceRelationshipPlugin(
        ResourceRelationshipPluginInterface $resourceRelationshipPlugin
    ): array;
}
