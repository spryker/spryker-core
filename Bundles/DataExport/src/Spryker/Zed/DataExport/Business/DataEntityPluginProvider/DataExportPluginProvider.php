<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Business\DataEntityPluginProvider;

use Spryker\Zed\DataExport\Business\Exception\DataExporterNotFoundException;
use Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityExporterPluginInterface;
use Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityGeneratorPluginInterface;
use Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityPluginInterface;
use Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityReaderPluginInterface;

class DataExportPluginProvider implements DataExportPluginProviderInterface
{
    /**
     * @var array<string, array<string, \Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityPluginInterface>>
     */
    protected array $dataEntityPlugins = [];

    /**
     * @param list<\Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityExporterPluginInterface> $dataEntityExporterPlugins
     * @param list<\Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityGeneratorPluginInterface> $dataExportDataEntityGeneratorPlugins
     * @param list<\Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityReaderPluginInterface> $dataExportDataEntityReaderPlugins
     */
    public function __construct(
        array $dataEntityExporterPlugins,
        array $dataExportDataEntityGeneratorPlugins,
        array $dataExportDataEntityReaderPlugins
    ) {
        $this->addDataEntityPluginsPerEntityAndInterface($dataEntityExporterPlugins, DataEntityExporterPluginInterface::class);
        $this->addDataEntityPluginsPerEntityAndInterface($dataExportDataEntityGeneratorPlugins, DataEntityGeneratorPluginInterface::class);
        $this->addDataEntityPluginsPerEntityAndInterface($dataExportDataEntityReaderPlugins, DataEntityReaderPluginInterface::class);
    }

    /**
     * @param string $dataEntityName
     * @param string|null $pluginInterface
     *
     * @return bool
     */
    public function hasDataEntityPlugin(string $dataEntityName, ?string $pluginInterface = null): bool
    {
        return $dataEntityName &&
                ($pluginInterface !== null
                ? isset($this->dataEntityPlugins[$dataEntityName][$pluginInterface])
                : isset($this->dataEntityPlugins[$dataEntityName]));
    }

    /**
     * @param string $dataEntityName
     * @param string|null $pluginInterface
     *
     * @throws \Spryker\Zed\DataExport\Business\Exception\DataExporterNotFoundException
     *
     * @return void
     */
    public function requireDataEntityPlugin(string $dataEntityName, ?string $pluginInterface = null): void
    {
        if (!$this->hasDataEntityPlugin($dataEntityName, $pluginInterface)) {
            throw new DataExporterNotFoundException(sprintf('Data export plugin not found for %s data entity', $dataEntityName));
        }
    }

    /**
     * @param string $dataEntityName
     * @param class-string<\Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityPluginInterface> $pluginInterface
     *
     * @return \Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityPluginInterface
     */
    public function getDataEntityPluginForInterface(string $dataEntityName, string $pluginInterface): DataEntityPluginInterface
    {
        $this->requireDataEntityPlugin($dataEntityName, $pluginInterface);

        return $this->dataEntityPlugins[$dataEntityName][$pluginInterface];
    }

    /**
     * @param string $dataEntityName
     *
     * @return \Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityPluginInterface|false
     */
    public function findDataEntityPlugin(string $dataEntityName): DataEntityPluginInterface|false
    {
        return reset($this->dataEntityPlugins[$dataEntityName]);
    }

    /**
     * @param list<\Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityPluginInterface> $dataEntityPlugins
     * @param string $pluginInterface
     *
     * @return void
     */
    protected function addDataEntityPluginsPerEntityAndInterface(array $dataEntityPlugins, string $pluginInterface): void
    {
        foreach ($dataEntityPlugins as $dataEntityPlugin) {
            $this->dataEntityPlugins[$dataEntityPlugin->getDataEntity()] = [$pluginInterface => $dataEntityPlugin];
        }
    }
}
