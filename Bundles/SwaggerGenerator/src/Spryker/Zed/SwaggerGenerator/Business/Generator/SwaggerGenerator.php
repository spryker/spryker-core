<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SwaggerGenerator\Business\Generator;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\GlueApplication\GlueApplicationConstants;
use Spryker\Shared\SwaggerGenerator\SwaggerGeneratorConstants;
use Symfony\Component\Yaml\Yaml;

class SwaggerGenerator implements SwaggerGeneratorInterface
{
    protected const GENERATED_FILE_POSTFIX = '.schema.yml';

    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface[]
     */
    protected $resourceRoutesPluginsProviderPlugins;

    /**
     * @var \Spryker\Zed\SwaggerGenerator\Business\Generator\SwaggerSchemaGeneratorInterface
     */
    protected $swaggerSchemaGenerator;

    /**
     * @var \Spryker\Zed\SwaggerGenerator\Business\Generator\SwaggerPathsGeneratorInterface
     */
    protected $swaggerPathGenerator;

    /**
     * SwaggerGenerator constructor.
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface[] $resourceRoutesPluginsProviderPlugins
     * @param \Spryker\Zed\SwaggerGenerator\Business\Generator\SwaggerSchemaGeneratorInterface $swaggerSchemaGenerator
     * @param \Spryker\Zed\SwaggerGenerator\Business\Generator\SwaggerPathsGeneratorInterface $swaggerPathGenerator
     */
    public function __construct(
        array $resourceRoutesPluginsProviderPlugins,
        SwaggerSchemaGeneratorInterface $swaggerSchemaGenerator,
        SwaggerPathsGeneratorInterface $swaggerPathGenerator
    ) {
        $this->resourceRoutesPluginsProviderPlugins = $resourceRoutesPluginsProviderPlugins;
        $this->swaggerSchemaGenerator = $swaggerSchemaGenerator;
        $this->swaggerPathGenerator = $swaggerPathGenerator;
    }

    /**
     * @return void
     */
    public function generate(): void
    {
        $data = $this->getDefaultDataStructure();

        $this->swaggerSchemaGenerator->addSchemaFromTransferClassName(RestErrorMessageTransfer::class);
        $errorTransferSchemaKey = $this->swaggerSchemaGenerator->getLastAddedSchemaKey();

        foreach ($this->resourceRoutesPluginsProviderPlugins as $resourceRoutesPluginsProviderPlugin) {
            foreach ($resourceRoutesPluginsProviderPlugin->getResourceRoutePlugins() as $plugin) {
                $this->swaggerSchemaGenerator->addSchemaFromTransferClassName($plugin->getResourceAttributesClassName());
                $transferSchemaKey = $this->swaggerSchemaGenerator->getLastAddedSchemaKey();
                $this->swaggerPathGenerator->addPathsForPlugin($plugin, $transferSchemaKey, $errorTransferSchemaKey);
            }
        }

        $data['components']['schemas'] = $this->swaggerSchemaGenerator->getSchemas();
        $data['paths'] = $this->swaggerPathGenerator->getPaths();

        file_put_contents($this->resolveGeneratedFileName(), Yaml::dump($data, 9));
    }

    /**
     * @return array
     */
    protected function getDefaultDataStructure(): array
    {
        return [
            'openapi' => '3.0.0',
            'info' => [
                'version' => '1.0.0',
                'title' => 'Spryker API',
                'license' => [
                    'name' => 'MIT',
                ],
            ],
            'servers' => [
                [
                    'url' => Config::get(GlueApplicationConstants::GLUE_APPLICATION_DOMAIN, ''),
                ],
            ],
            'paths' => [],
            'components' => [
                'schemas' => [],
            ],
        ];
    }

    /**
     * @return string
     */
    protected function resolveGeneratedFileName(): string
    {
        $fileName = Config::get(SwaggerGeneratorConstants::SWAGGER_GENERATOR_FILE_NAME);

        if (substr($fileName, -\strlen(static::GENERATED_FILE_POSTFIX)) === static::GENERATED_FILE_POSTFIX) {
            return $fileName;
        }

        return $fileName . static::GENERATED_FILE_POSTFIX;
    }
}
