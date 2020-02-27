<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;

class ResourceRelationshipsPluginAnalyzer implements ResourceRelationshipsPluginAnalyzerInterface
{
    /**
     * @var \Spryker\Glue\DocumentationGeneratorRestApiExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface[]
     */
    protected $resourceRelationshipCollectionPlugins;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnnotationAnalyzerInterface
     */
    protected $resourceRelationshipsPluginAnnotationAnalyzer;

    /**
     * @param \Spryker\Glue\DocumentationGeneratorRestApiExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface[] $resourceRelationshipCollectionPlugins
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourceRelationshipsPluginAnnotationAnalyzerInterface $resourceRelationshipsPluginAnnotationAnalyzer
     */
    public function __construct(
        array $resourceRelationshipCollectionPlugins,
        ResourceRelationshipsPluginAnnotationAnalyzerInterface $resourceRelationshipsPluginAnnotationAnalyzer
    ) {
        $this->resourceRelationshipCollectionPlugins = $resourceRelationshipCollectionPlugins;
        $this->resourceRelationshipsPluginAnnotationAnalyzer = $resourceRelationshipsPluginAnnotationAnalyzer;
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
                $this->resourceRelationshipsPluginAnnotationAnalyzer->getResourceAttributesFromResourceRelationshipPlugin($relationshipPlugin);
                $resourceRelationships[] = $relationshipPlugin->getRelationshipResourceType();
            }
        }

        return $resourceRelationships;
    }
}
