<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiConfig;

class ResourceRelationshipsPluginAnalyzer implements ResourceRelationshipsPluginAnalyzerInterface
{
    /**
     * @var array<\Spryker\Glue\DocumentationGeneratorRestApiExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface>
     */
    protected $resourceRelationshipCollectionPlugins;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiConfig
     */
    protected DocumentationGeneratorRestApiConfig $documentationGeneratorRestApiConfig;

    /**
     * @param array<\Spryker\Glue\DocumentationGeneratorRestApiExtension\Dependency\Plugin\ResourceRelationshipCollectionProviderPluginInterface> $resourceRelationshipCollectionPlugins
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiConfig $documentationGeneratorRestApiConfig
     */
    public function __construct(
        array $resourceRelationshipCollectionPlugins,
        DocumentationGeneratorRestApiConfig $documentationGeneratorRestApiConfig
    ) {
        $this->resourceRelationshipCollectionPlugins = $resourceRelationshipCollectionPlugins;
        $this->documentationGeneratorRestApiConfig = $documentationGeneratorRestApiConfig;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return array<string, \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface>
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
                $resourceRelationships[$relationshipPlugin->getRelationshipResourceType()] = $relationshipPlugin;
            }
        }

        return $resourceRelationships;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $resourceRoutePlugin
     *
     * @return array<string, \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface>
     */
    public function getNestedResourceRelationshipsForResourceRoutePlugin(ResourceRoutePluginInterface $resourceRoutePlugin): array
    {
        if (!$this->documentationGeneratorRestApiConfig->isNestedRelationshipsEnabled()) {
            return $this->getResourceRelationshipsForResourceRoutePlugin($resourceRoutePlugin);
        }

        $mappedResourceRelationshipPlugins = [];

        foreach ($this->resourceRelationshipCollectionPlugins as $resourceRelationshipCollectionPlugin) {
            $resourceRelationshipCollection = $resourceRelationshipCollectionPlugin->getResourceRelationshipCollection();

            if (!$resourceRelationshipCollection->hasRelationships($resourceRoutePlugin->getResourceType())) {
                continue;
            }

            $mappedResourceRelationshipPlugins = $this->getNestedResourceRelationshipsForResourceRelationshipPlugins(
                $resourceRelationshipCollection->getRelationships($resourceRoutePlugin->getResourceType()),
                $mappedResourceRelationshipPlugins,
            );
        }

        return $mappedResourceRelationshipPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface $resourceRelationshipPlugin
     *
     * @return array<string, \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface>
     */
    public function getResourceRelationshipsForResourceRelationshipPlugin(
        ResourceRelationshipPluginInterface $resourceRelationshipPlugin
    ): array {
        $resourceRelationships = [];

        foreach ($this->resourceRelationshipCollectionPlugins as $resourceRelationshipCollectionPlugin) {
            $resourceRelationshipCollection = $resourceRelationshipCollectionPlugin->getResourceRelationshipCollection();

            if (!$resourceRelationshipCollection->hasRelationships($resourceRelationshipPlugin->getRelationshipResourceType())) {
                continue;
            }

            $resourceRelationshipPlugins = $resourceRelationshipCollection->getRelationships($resourceRelationshipPlugin->getRelationshipResourceType());

            foreach ($resourceRelationshipPlugins as $relationshipPlugin) {
                $resourceRelationships[$relationshipPlugin->getRelationshipResourceType()] = $relationshipPlugin;
            }
        }

        return $resourceRelationships;
    }

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface> $resourceRelationshipPlugins
     * @param array<string, \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface> $mappedResourceRelationshipPlugins
     *
     * @return array<string, \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface>
     */
    protected function getNestedResourceRelationshipsForResourceRelationshipPlugins(
        array $resourceRelationshipPlugins,
        array $mappedResourceRelationshipPlugins
    ): array {
        foreach ($resourceRelationshipPlugins as $resourceRelationshipPlugin) {
            $mappedResourceRelationshipPlugins[$resourceRelationshipPlugin->getRelationshipResourceType()] = $resourceRelationshipPlugin;

            $mappedResourceRelationshipPlugins = $this->getNetstedResourceRelationships(
                $resourceRelationshipPlugin,
                $mappedResourceRelationshipPlugins,
            );
        }

        return $mappedResourceRelationshipPlugins;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface $resourceRelationshipPlugin
     * @param array<string, \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface> $mappedResourceRelationshipPlugins
     *
     * @return array<string, \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface>
     */
    protected function getNetstedResourceRelationships(
        ResourceRelationshipPluginInterface $resourceRelationshipPlugin,
        array $mappedResourceRelationshipPlugins
    ): array {
        foreach ($this->resourceRelationshipCollectionPlugins as $resourceRelationshipCollectionPlugin) {
            $resourceRelationshipCollection = $resourceRelationshipCollectionPlugin->getResourceRelationshipCollection();

            if (!$resourceRelationshipCollection->hasRelationships($resourceRelationshipPlugin->getRelationshipResourceType())) {
                continue;
            }

            $resourceRelationshipPlugins = $resourceRelationshipCollection->getRelationships($resourceRelationshipPlugin->getRelationshipResourceType());

            foreach ($resourceRelationshipPlugins as $relationshipPlugin) {
                if (isset($mappedResourceRelationshipPlugins[$relationshipPlugin->getRelationshipResourceType()])) {
                    continue;
                }

                $mappedResourceRelationshipPlugins[$relationshipPlugin->getRelationshipResourceType()] = $relationshipPlugin;
                $mappedResourceRelationshipPlugins = $this->getNetstedResourceRelationships($relationshipPlugin, $mappedResourceRelationshipPlugins);
            }
        }

        return $mappedResourceRelationshipPlugins;
    }
}
