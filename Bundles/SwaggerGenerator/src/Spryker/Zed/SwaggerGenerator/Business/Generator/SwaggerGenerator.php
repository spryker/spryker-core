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

    protected const OPENAPI_VERSION = '3.0.0';

    protected const KEY_OPENAPI = 'openapi';
    protected const KEY_INFO = 'info';
    protected const KEY_VERSION = 'version';
    protected const KEY_TITLE = 'title';
    protected const KEY_LICENSE = 'license';
    protected const KEY_NAME = 'name';
    protected const KEY_SERVERS = 'servers';
    protected const KEY_URL = 'url';
    protected const KEY_PATHS = 'paths';
    protected const KEY_COMPONENTS = 'components';
    protected const KEY_SCHEMAS = 'schemas';

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

        $data[static::KEY_COMPONENTS][static::KEY_SCHEMAS] = $this->swaggerSchemaGenerator->getSchemas();
        $data[static::KEY_PATHS] = $this->swaggerPathGenerator->getPaths();

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
            static::KEY_OPENAPI => static::OPENAPI_VERSION,
            static::KEY_INFO => [
                static::KEY_VERSION => $this->swaggerGeneratorConfig->getInfoApiVersion(),
                static::KEY_TITLE => $this->swaggerGeneratorConfig->getInfoApiTitle(),
                static::KEY_LICENSE => [
                    static::KEY_NAME => $this->swaggerGeneratorConfig->getInfoApiInfoLicenceName(),
                ],
            ],
            static::KEY_SERVERS => [
                [
                    static::KEY_URL => $this->swaggerGeneratorConfig->getRestApplicationDomain(),
                ],
            ],
            static::KEY_PATHS => [],
            static::KEY_COMPONENTS => [
                static::KEY_SCHEMAS => [],
            ],
        ];
    }

    /**
     * @return string
     */
    protected function resolveGeneratedFileName(): string
    {
        $fileName = $this->swaggerGeneratorConfig->getGeneratedFileName();

        if (substr_compare($fileName, static::GENERATED_FILE_POSTFIX, -strlen(static::GENERATED_FILE_POSTFIX)) === 0) {
            return $fileName;
        }

        return $fileName . static::GENERATED_FILE_POSTFIX;
    }
}
