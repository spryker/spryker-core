<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Generator;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceWithParentPluginInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface;

class RestApiDocumentationGenerator implements RestApiDocumentationGeneratorInterface
{
    /**
     * @var \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface[]
     */
    protected $resourceRoutesPluginsProviderPlugins;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface
     */
    protected $restApiSchemaGenerator;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface
     */
    protected $restApiPathGenerator;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface
     */
    protected $restApiDocumentationWriter;

    /**
     * @param \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface[] $resourceRoutesPluginsProviderPlugins
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface $restApiSchemaGenerator
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface $restApiPathGenerator
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface $restApiDocumentationWriter
     */
    public function __construct(
        array $resourceRoutesPluginsProviderPlugins,
        RestApiDocumentationSchemaGeneratorInterface $restApiSchemaGenerator,
        RestApiDocumentationPathGeneratorInterface $restApiPathGenerator,
        RestApiDocumentationWriterInterface $restApiDocumentationWriter
    ) {
        $this->resourceRoutesPluginsProviderPlugins = $resourceRoutesPluginsProviderPlugins;
        $this->restApiSchemaGenerator = $restApiSchemaGenerator;
        $this->restApiPathGenerator = $restApiPathGenerator;
        $this->restApiDocumentationWriter = $restApiDocumentationWriter;
    }

    /**
     * @return void
     */
    public function generateOpenApiSpecification(): void
    {
        foreach ($this->resourceRoutesPluginsProviderPlugins as $resourceRoutesPluginsProviderPlugin) {
            foreach ($resourceRoutesPluginsProviderPlugin->getResourceRoutePlugins() as $plugin) {
                $parents = $this->getParentResource($plugin, $resourceRoutesPluginsProviderPlugin->getResourceRoutePlugins());
                $this->restApiPathGenerator->addPathsForPlugin($plugin, $parents);
            }
        }

        $this->restApiDocumentationWriter->write(
            $this->restApiPathGenerator->getPaths(),
            $this->restApiSchemaGenerator->getSchemas()
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     * @param array $pluginsStack
     *
     * @return array|null
     */
    protected function getParentResource(ResourceRoutePluginInterface $plugin, array $pluginsStack): ?array
    {
        if ($plugin instanceof ResourceWithParentPluginInterface) {
            $parent = [];
            foreach ($pluginsStack as $parentPlugin) {
                if ($plugin->getParentResourceType() === $parentPlugin->getResourceType()) {
                    $parent = [
                        'name' => $parentPlugin->getResourceType(),
                        'id' => '{' . $parentPlugin->getResourceType() . '-id}',
                        'parent' => $this->getParentResource($parentPlugin, $pluginsStack),
                    ];
                    break;
                }
            }
            return $parent;
        }

        return null;
    }
}
