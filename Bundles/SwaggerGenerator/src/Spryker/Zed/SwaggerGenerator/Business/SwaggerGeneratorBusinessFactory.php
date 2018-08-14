<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SwaggerGenerator\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SwaggerGenerator\Business\Generator\SwaggerGenerator;
use Spryker\Zed\SwaggerGenerator\Business\Generator\SwaggerGeneratorInterface;
use Spryker\Zed\SwaggerGenerator\Business\Generator\SwaggerPathGenerator;
use Spryker\Zed\SwaggerGenerator\Business\Generator\SwaggerPathGeneratorInterface;
use Spryker\Zed\SwaggerGenerator\Business\Generator\SwaggerSchemaGenerator;
use Spryker\Zed\SwaggerGenerator\Business\Generator\SwaggerSchemaGeneratorInterface;
use Spryker\Zed\SwaggerGenerator\SwaggerGeneratorDependencyProvider;

/**
 * @method \Spryker\Zed\SwaggerGenerator\SwaggerGeneratorConfig getConfig()
 */
class SwaggerGeneratorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SwaggerGenerator\Business\Generator\SwaggerGeneratorInterface
     */
    public function createGenerator(): SwaggerGeneratorInterface
    {
        return new SwaggerGenerator(
            $this->getResourceRoutesPluginsProviderPlugins(),
            $this->createSwaggerSchemaGenerator(),
            $this->createSwaggerPathsGenerator(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\SwaggerGenerator\Business\Generator\SwaggerSchemaGeneratorInterface
     */
    public function createSwaggerSchemaGenerator(): SwaggerSchemaGeneratorInterface
    {
        return new SwaggerSchemaGenerator();
    }

    /**
     * @return \Spryker\Zed\SwaggerGenerator\Business\Generator\SwaggerPathGeneratorInterface
     */
    public function createSwaggerPathsGenerator(): SwaggerPathGeneratorInterface
    {
        return new SwaggerPathGenerator();
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface[]
     */
    public function getResourceRoutesPluginsProviderPlugins(): array
    {
        return $this->getProvidedDependency(SwaggerGeneratorDependencyProvider::PLUGIN_RESOURCE_ROUTES_RESOLVER);
    }
}
