<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Export;

use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataQueryContainerPluginInterface;
use Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataRepositoryPluginInterface;

class ExporterPluginResolver implements ExporterPluginResolverInterface
{
    public const REPOSITORY_SYNCHRONIZATION_PLUGINS = 'repository';
    public const QUERY_CONTAINER_SYNCHRONIZATION_PLUGINS = 'query_container';

    /**
     * @var \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface[]
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
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface[] $synchronizationDataPlugins
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
     * @deprecated Use `Spryker\Zed\Synchronization\Business\Export\ExporterPluginResolver::executeResolvedPluginsBySourcesWithIds()` instead.
     *
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
     * @param string[] $resources
     * @param int[] $ids
     *
     * @return void
     */
    public function executeResolvedPluginsBySourcesWithIds(array $resources, array $ids): void
    {
        $pluginsPerExporter = $this->getResolvedPluginsByResources($resources);
        $this->queryContainerExporter->exportSynchronizedData($pluginsPerExporter[ExporterPluginResolver::QUERY_CONTAINER_SYNCHRONIZATION_PLUGINS]);
        $this->repositoryExporter->exportSynchronizedData($pluginsPerExporter[ExporterPluginResolver::REPOSITORY_SYNCHRONIZATION_PLUGINS], $ids);
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

        return array_unique($resourceNames);
    }

    /**
     * @param string[] $resources
     *
     * @return array
     */
    protected function getResolvedPluginsByResources(array $resources): array
    {
        $mappedPluginsByResourceName = $this->mapPluginsByResourceAndQueueName($this->synchronizationDataPlugins);
        $effectivePluginsByResource = $this->filterEffectivePlugins($resources, $mappedPluginsByResourceName);
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
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface[][] $synchronizationDataPlugins
     *
     * @return \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface[][]
     */
    protected function filterEffectivePlugins(array $resources, array $synchronizationDataPlugins): array
    {
        if ($resources === []) {
            return $synchronizationDataPlugins;
        }

        $effectivePlugins = [];
        foreach ($resources as $resource) {
            if (isset($synchronizationDataPlugins[$resource])) {
                $effectivePlugins[$resource] = $synchronizationDataPlugins[$resource];
            }
        }

        return $effectivePlugins;
    }

    /**
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface[] $synchronizationDataPlugins
     *
     * @return \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface[][]
     */
    protected function mapPluginsByResourceAndQueueName(array $synchronizationDataPlugins): array
    {
        $mappedDataPlugins = [];
        foreach ($synchronizationDataPlugins as $plugin) {
            $mappedDataPlugins[$plugin->getResourceName()][$plugin->getQueueName()] = $plugin;
        }

        return $mappedDataPlugins;
    }

    /**
     * @param \Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataPluginInterface[]|\Spryker\Zed\SynchronizationExtension\Dependency\Plugin\SynchronizationDataBulkRepositoryPluginInterface[] $effectivePlugins
     * @param array $pluginsPerExporter
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
