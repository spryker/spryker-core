<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SwaggerGenerator\Business\Generator;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Zed\SwaggerGenerator\Business\Exception\FileNotCreatedException;
use Spryker\Zed\SwaggerGenerator\SwaggerGeneratorConfig;
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
     * @var \Spryker\Zed\SwaggerGenerator\Business\Generator\SwaggerPathGeneratorInterface
     */
    protected $swaggerPathGenerator;

    /**
     * @var \Spryker\Zed\SwaggerGenerator\SwaggerGeneratorConfig
     */
    protected $swaggerGeneratorConfig;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface[] $resourceRoutesPluginsProviderPlugins
     * @param \Spryker\Zed\SwaggerGenerator\Business\Generator\SwaggerSchemaGeneratorInterface $swaggerSchemaGenerator
     * @param \Spryker\Zed\SwaggerGenerator\Business\Generator\SwaggerPathGeneratorInterface $swaggerPathGenerator
     * @param \Spryker\Zed\SwaggerGenerator\SwaggerGeneratorConfig $swaggerGeneratorConfig
     */
    public function __construct(
        array $resourceRoutesPluginsProviderPlugins,
        SwaggerSchemaGeneratorInterface $swaggerSchemaGenerator,
        SwaggerPathGeneratorInterface $swaggerPathGenerator,
        SwaggerGeneratorConfig $swaggerGeneratorConfig
    ) {
        $this->resourceRoutesPluginsProviderPlugins = $resourceRoutesPluginsProviderPlugins;
        $this->swaggerSchemaGenerator = $swaggerSchemaGenerator;
        $this->swaggerPathGenerator = $swaggerPathGenerator;
        $this->swaggerGeneratorConfig = $swaggerGeneratorConfig;
    }

    /**
     * @throws \Spryker\Zed\SwaggerGenerator\Business\Exception\FileNotCreatedException
     *
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

        $bytesWritten = file_put_contents($this->resolveGeneratedFileName(), Yaml::dump($data, 9));
        if (!$bytesWritten) {
            throw new FileNotCreatedException('Unable to create file, please check permissions and free space available on device');
        }
    }

    /**
     * @return array
     */
    protected function getDefaultDataStructure(): array
    {
        return [
            'openapi' => '3.0.0',
            'info' => [
                'version' => $this->swaggerGeneratorConfig->getInfoApiVersion(),
                'title' => $this->swaggerGeneratorConfig->getInfoApiTitle(),
                'license' => [
                    'name' => $this->swaggerGeneratorConfig->getInfoApiInfoLicenceName(),
                ],
            ],
            'servers' => [
                [
                    'url' => $this->swaggerGeneratorConfig->getRestApplicationDomain(),
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
        $fileName = $this->swaggerGeneratorConfig->getGeneratedFileName();

        if (substr($fileName, -\strlen(static::GENERATED_FILE_POSTFIX)) === static::GENERATED_FILE_POSTFIX) {
            return $fileName;
        }

        return $fileName . static::GENERATED_FILE_POSTFIX;
    }
}
