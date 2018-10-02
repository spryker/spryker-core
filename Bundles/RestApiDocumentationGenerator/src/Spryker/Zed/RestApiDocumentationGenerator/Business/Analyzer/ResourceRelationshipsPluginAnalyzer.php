<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;

class ResourceRelationshipsPluginAnalyzer implements ResourceRelationshipsPluginAnalyzerInterface
{
    /**
     * @var \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface[]
     */
    protected $resourceRelationshipCollectionPlugins;

    /**
     * @param \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface[] $resourceRelationshipCollectionPlugins
     */
    public function __construct(array $resourceRelationshipCollectionPlugins)
    {
        $this->resourceRelationshipCollectionPlugins = $resourceRelationshipCollectionPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return array
     */
    public function getResourceRelationshipsForResourceRoutePlugin(ResourceRoutePluginInterface $plugin): array
    {
        $resourceRelationships = [];
        foreach ($this->resourceRelationshipCollectionPlugins as $resourceRelationshipCollectionPlugin) {
            $resourceRouteCollection = $resourceRelationshipCollectionPlugin->getResourceRelationshipCollection();
            if (!$resourceRouteCollection->hasRelationships($plugin->getResourceType())) {
                continue;
            }
            $relationshipPlugins = $resourceRouteCollection->getRelationships($plugin->getResourceType());
            foreach ($relationshipPlugins as $relationshipPlugin) {
                $resourceRelationships[] = $relationshipPlugin->getRelationshipResourceType();
            }
        }

        return $resourceRelationships;
    }
}
