<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Cache\Writer;

use Spryker\Glue\GlueApplication\Dependency\External\GlueApplicationToSymfonyFilesystemInterface;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;

class ControllerCacheWriter implements ControllerCacheWriterInterface
{
    /**
     * @var array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerCacheCollectorPluginInterface>
     */
    protected $controllerCacheCollectorPlugins = [];

    /**
     * @var \Spryker\Glue\GlueApplication\GlueApplicationConfig
     */
    protected $config;

    /**
     * @var \Spryker\Glue\GlueApplication\Dependency\External\GlueApplicationToSymfonyFilesystemInterface
     */
    protected $filesystem;

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerCacheCollectorPluginInterface> $controllerCacheCollectorPlugins
     * @param \Spryker\Glue\GlueApplication\GlueApplicationConfig $config
     * @param \Spryker\Glue\GlueApplication\Dependency\External\GlueApplicationToSymfonyFilesystemInterface $filesystem
     */
    public function __construct(
        array $controllerCacheCollectorPlugins,
        GlueApplicationConfig $config,
        GlueApplicationToSymfonyFilesystemInterface $filesystem
    ) {
        $this->controllerCacheCollectorPlugins = $controllerCacheCollectorPlugins;
        $this->config = $config;
        $this->filesystem = $filesystem;
    }

    /**
     * @return void
     */
    public function cache(): void
    {
        $apiControllerConfigurationTransfersData = [];

        foreach ($this->controllerCacheCollectorPlugins as $controllerCacheCollectorPlugin) {
            $apiControllerConfigurationTransfersData = array_merge($controllerCacheCollectorPlugin->getControllerConfiguration(), $apiControllerConfigurationTransfersData);
        }

        $this->filesystem->dumpFile(
            $this->config->getControllerCachePath() . DIRECTORY_SEPARATOR . GlueApplicationConfig::API_CONTROLLER_CACHE_FILENAME,
            serialize($apiControllerConfigurationTransfersData),
        );
    }
}
