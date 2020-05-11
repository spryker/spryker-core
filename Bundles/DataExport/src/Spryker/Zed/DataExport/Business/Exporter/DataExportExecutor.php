<?php

namespace Spryker\Zed\DataExport\Business\Exporter;

use Generated\Shared\Transfer\DataExportConfigurationsTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;
use Spryker\Service\DataExport\DataExportServiceInterface;
use Spryker\Zed\DataExport\Business\Exception\DataExporterNotFoundException;
use Spryker\Zed\DataExport\DataExportConfig;
use Spryker\Zed\DataExport\Dependency\Plugin\DataEntityExporterPluginInterface;

class DataExportExecutor
{
    protected const HOOK_KEY_EXTENSION = 'extension';

    /**
     * @var \Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityExporterPluginInterface[]
     */
    protected $dataEntityExporterPlugins;


    /**
     * @var \Spryker\Service\DataExport\DataExportServiceInterface
     */
    protected $dataExportService;

    /**
     * @var \Spryker\Zed\DataExport\DataExportConfig
     */
    protected $dataExportConfig;

    public function __construct(
        array $dataEntityExporterPlugins,
        DataExportServiceInterface $dataExportService,
        DataExportConfig $dataExportConfig
    ) {
        $this->dataEntityExporterPlugins = $dataEntityExporterPlugins;
        $this->dataExportService = $dataExportService;
        $this->dataExportConfig = $dataExportConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationsTransfer $dataExportConfigurationsTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer[]
     */
    public function exportDataEntities(DataExportConfigurationsTransfer $dataExportConfigurationsTransfer): array
    {
        $dataExportResultTransfers = [];

        $dataExportDefaultsConfigurationsTransfer = $this->getDataExportDefaultsConfiguration();
        $dataExportDefaultsConfigurationTransfer = $this->dataExportService->mergeDataExportConfigurations(
            $dataExportConfigurationsTransfer->getDefaults() ?? new DataExportConfigurationTransfer(),
            $dataExportDefaultsConfigurationsTransfer->getDefaults()
        );

        foreach($dataExportConfigurationsTransfer->getActions() as $dataExportConfigurationTransfer) {
            $dataExportConfigurationTransfer = $this->dataExportService->mergeDataExportConfigurations(
                $dataExportDefaultsConfigurationTransfer,
                $dataExportConfigurationTransfer
            );
            $dataExportConfigurationTransfer = $this->addDataExportConfigurationActionHooks($dataExportConfigurationTransfer);

            $dataExportResultTransfers[] = $this->runExport($dataExportConfigurationTransfer);
        }

        return $dataExportResultTransfers;
    }

    /**
     * @return \Generated\Shared\Transfer\DataExportConfigurationsTransfer
     */
    protected function getDataExportDefaultsConfiguration(): DataExportConfigurationsTransfer
    {
        return $this->dataExportService->parseConfiguration(
            $this->dataExportConfig->getExportConfigurationDefaultsPath()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    protected function runExport(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer
    {
        foreach ($this->dataEntityExporterPlugins as $dataEntityExporterPlugin) {
            if (!$dataEntityExporterPlugin->isApplicable($dataExportConfigurationTransfer)) {
                continue;
            }

            return $dataEntityExporterPlugin->export($dataExportConfigurationTransfer);
        }

        throw new DataExporterNotFoundException(sprintf(
            'Data exporter not found for %s data entity',
            $dataExportConfigurationTransfer->getDataEntity())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    protected function addDataExportConfigurationActionHooks(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportConfigurationTransfer
    {
        $dataExportConfigurationTransfer->addHook(
            static::HOOK_KEY_EXTENSION,
            $this->dataExportService->getFormatExtension($dataExportConfigurationTransfer)
        );

        return $dataExportConfigurationTransfer;
    }

    /**
     * @param array $exportConfiguration
     *
     * @throws \Exception
     *
     * @return DataEntityExporterPluginInterface
     */
    protected function pickDataEntityExporter(array $exportConfiguration): DataEntityExporterPluginInterface
    {
        foreach($this->dataEntityExporterPlugins as $dataEntityExporterPlugin) {
            if ($dataEntityExporterPlugin->isApplicable($exportConfiguration)) {
                return $dataEntityExporterPlugin;
            }
        }

        throw new \Exception('No registered data entity exporter is applicable for the requested export configuration: ' . $exportConfiguration['data_entity']);
    }

    /**
     * Note: Applying "defaults" from Bundles/DataExport/data/config/defaults_config.yml on each action of application_root/data/export/config/order_export_config.yml
     * Note: Adds "hooks" for the magic file naming, eg: {application_root_dir}/data/export/myfile_{timestamp}.{extension}
     *
     * @param array $exportConfigurations
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function applyGlobalDataExportConfigurationsAdjuster(array $exportConfigurations): array {
        $globalExportConfigurationDefaults = $this->dataExportService->parseConfiguration($this->dataExportConfig->getExportConfigurationDefaultsPath());

        $exportConfigurations['defaults'] = ($exportConfigurations['defaults'] ?? []) + ($globalExportConfigurationDefaults['defaults'] ?? []);
        foreach($exportConfigurations['actions'] as &$exportConfiguration) {
            $exportConfiguration += $exportConfigurations['defaults'];
            $exportConfiguration['hooks'] = [
                'timestamp' => time(),
                'application_root_dir' => APPLICATION_ROOT_DIR,
                'extension' => $this->getWriterExtension($exportConfiguration),
            ];
        }

        return $exportConfigurations;
    }

    /**
     * Note: data entity specific configuration adjustments, eg: /Bundles/SalesDataExport/data/export/config/sales_export_config.yml
     *
     * @param array $exportConfigurations
     *
     * @return array
     */
    protected function applyDataExportConfigurationAdjusterPlugins(array $exportConfigurations): array{
        foreach($this->dataExportConfigurationAdjusterPlugins as $dataEntityConfigurationDefaultsPlugin) {
            if ($dataEntityConfigurationDefaultsPlugin->isApplicable($exportConfigurations)) {
                $exportConfigurations = $dataEntityConfigurationDefaultsPlugin->adjustExportConfiguration($exportConfigurations);
            }
        }

        return $exportConfigurations;
    }

    /**
     * Note: figures out writer (csv is in-built!)
     *
     * @param array $exportConfiguration
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getWriterExtension(array $exportConfiguration):string
    {
        foreach ($this->dataExportWriterPlugins as $exportWriterPlugin) {
            if ($exportWriterPlugin->isApplicable($exportConfiguration)) {
                return $exportWriterPlugin->getExtension($exportConfiguration);
            }
        }

        if ($exportConfiguration['writer']['type'] === 'csv') {
            return 'csv';
        }

        throw new \Exception('No writer is configured');
    }
}
