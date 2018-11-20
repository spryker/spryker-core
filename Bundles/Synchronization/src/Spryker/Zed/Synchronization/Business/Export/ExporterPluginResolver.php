<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Export;

use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface;

class ExporterPluginResolver
{
    public const REPOSITORY_SYNCHRONIZATION_PLUGINS = 'repository';
    public const QUERY_CONTAINER_SYNCHRONIZATION_PLUGINS = 'query_container';

    /**
     * @var array
     */
    protected $synchronizationDataPlugins;

    /**
     * @var \Spryker\Zed\Synchronization\Business\Export\QueryContainerExporter
     */
    protected $queryContainerExporter;

    /**
     * @var \Spryker\Zed\Synchronization\Business\Export\RepositoryExporter
     */
    protected $repositoryExporter;

    /**
     * @param array $synchronizationDataPlugins
     * @param \Spryker\Zed\Synchronization\Business\Export\QueryContainerExporter $queryContainerExporter
     * @param \Spryker\Zed\Synchronization\Business\Export\RepositoryExporter $repositoryExporter
     */
    public function __construct(
        array $synchronizationDataPlugins,
        QueryContainerExporter $queryContainerExporter,
        RepositoryExporter $repositoryExporter
    ) {
        $this->synchronizationDataPlugins = $synchronizationDataPlugins;
        $this->queryContainerExporter = $queryContainerExporter;
        $this->repositoryExporter = $repositoryExporter;
    }

    /**
     * @param string[] $resources
     *
     * @return void
     */
    public function executeResolvedPluginsBySources(array $resources): void
    {
        $pluginsPerExporter = $this->getResolvedPluginsByResources($resources);
        $this->queryContainerExporter->exportSynchronizedData($pluginsPerExporter[ExporterPluginResolver::QUERY_CONTAINER_SYNCHRONIZATION_PLUGINS]);
        $this->repositoryExporter->exportSynchronizedData($pluginsPerExporter[ExporterPluginResolver::REPOSITORY_SYNCHRONIZATION_PLUGINS]);
    }

    /**
     * @return string[]
     */
    public function getAvailableResourceNames(): array
    {
        $resourceNames = [];
        foreach ($this->synchronizationDataPlugins as $plugin) {
            $resourceNames[] = $plugin->getResourceName();
        }

        sort($resourceNames);

        return $resourceNames;
    }

    /**
     * @param string[] $resources
     *
     * @return array
     */
    protected function getResolvedPluginsByResources(array $resources): array
    {
        $this->mapPluginsByResourceName();
        $effectivePluginsByResource = $this->getEffectivePlugins($resources);
        $pluginsPerExporter = [
            static::REPOSITORY_SYNCHRONIZATION_PLUGINS => [],
            static::QUERY_CONTAINER_SYNCHRONIZATION_PLUGINS => [],
        ];

        foreach ($effectivePluginsByResource as $effectivePlugins) {
            $pluginsPerExporter = $this->extractEffectivePlugins($effectivePlugins, $pluginsPerExporter);
        }

        return $pluginsPerExporter;
    }

    /**
     * @param string[] $resources
     *
     * @return array
     */
    protected function getEffectivePlugins(array $resources): array
    {
        $effectivePlugins = [];
        if (empty($resources)) {
            return $this->synchronizationDataPlugins;
        }

        foreach ($resources as $resource) {
            if (isset($this->synchronizationDataPlugins[$resource])) {
                $effectivePlugins[$resource] = $this->synchronizationDataPlugins[$resource];
            }
        }

        return $effectivePlugins;
    }

    /**
     * @return void
     */
    protected function mapPluginsByResourceName(): void
    {
        $mappedDataPlugins = [];
        foreach ($this->synchronizationDataPlugins as $plugin) {
            $mappedDataPlugins[$plugin->getResourceName()][] = $plugin;
        }

        $this->synchronizationDataPlugins = $mappedDataPlugins;
    }

    /**
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface[]|\Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface[] $effectivePlugins
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface[] $pluginsPerExporter
     *
     * @return array
     */
    protected function extractEffectivePlugins($effectivePlugins, $pluginsPerExporter): array
    {
        foreach ($effectivePlugins as $effectivePlugin) {
            if ($effectivePlugin instanceof SynchronizationDataRepositoryPluginInterface || $effectivePlugin instanceof SynchronizationDataBulkRepositoryPluginInterface) {
                $pluginsPerExporter[static::REPOSITORY_SYNCHRONIZATION_PLUGINS][] = $effectivePlugin;
            }
            if ($effectivePlugin instanceof SynchronizationDataQueryContainerPluginInterface) {
                $pluginsPerExporter[static::QUERY_CONTAINER_SYNCHRONIZATION_PLUGINS][] = $effectivePlugin;
            }
        }

        return $pluginsPerExporter;
    }
}
