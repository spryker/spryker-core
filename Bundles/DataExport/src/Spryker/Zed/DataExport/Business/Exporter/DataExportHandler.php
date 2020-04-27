<?php

namespace Spryker\Zed\DataExport\Business\Exporter;

use Generated\Shared\Transfer\DataExportReportTransfer;
use Spryker\Service\DataExport\DataExportService;
use Spryker\Zed\DataExport\DataExportConfig;
use Spryker\Zed\DataExport\Dependency\Plugin\DataExportConfigurationAdjusterPluginInterface;
use Spryker\Zed\DataExport\Dependency\Plugin\DataEntityExporterPluginInterface;
use Spryker\Zed\DataExport\Dependency\Plugin\DataExportConnectionPluginInterface;
use Spryker\Zed\DataExport\Dependency\Plugin\DataExportWriterPluginInterface;

class DataExportHandler
{
    /**
     * @var DataEntityExporterPluginInterface[]
     */
    protected $dataEntityExporterPlugins;

    /** @var DataExportConnectionPluginInterface[] */
    protected $dataExportConnectionPlugins;

    /** @var DataExportWriterPluginInterface[] */
    protected $dataExportWriterPlugins;

    /** @var DataExportConfigurationAdjusterPluginInterface[] */
    protected $dataExportConfigurationAdjusterPlugins;

    /** @var DataExportService */
    protected $dataExportService;

    /**
     * @var DataExportConfig
     */
    protected $dataExportConfig;

    public function __construct(array $dataEntityExporterPlugins, array $dataExportConnectionPlugins, array $dataExportWriterPlugins, array $dataExportConfigurationAdjusterPlugins, DataExportService $dataExportService, DataExportConfig $dataExportConfig)
    {
        $this->dataEntityExporterPlugins = $dataEntityExporterPlugins;
        $this->dataExportConnectionPlugins = $dataExportConnectionPlugins;
        $this->dataExportWriterPlugins = $dataExportWriterPlugins;
        $this->dataExportConfigurationAdjusterPlugins = $dataExportConfigurationAdjusterPlugins;
        $this->dataExportService = $dataExportService;
        $this->dataExportConfig = $dataExportConfig;
    }

    /**
     * @param array $exportConfigurations
     *
     * @throws \Exception
     *
     * @return DataExportReportTransfer
     */
    public function exportBatch(array $exportConfigurations): DataExportReportTransfer {
        $dataExportResultTransfer = (new DataExportReportTransfer())
            ->setIsSuccess(true);

        $this->assertExportConfigurations($exportConfigurations);

        $exportConfigurations = $this->applyGlobalDataExportConfigurationsAdjuster($exportConfigurations);
        foreach($exportConfigurations['actions'] as $exportConfiguration) {
            $exportConfiguration = $this->applyDataExportConfigurationAdjusterPlugins($exportConfiguration);

            $dataEntityExporter = $this->pickDataEntityExporter($exportConfiguration);

            $dataExportResultTransfer->addResults(
                $dataEntityExporter->exportBatch($exportConfiguration)
            );
        }

        return $dataExportResultTransfer;
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
     * @param array $exportConfigurations
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function assertExportConfigurations(array $exportConfigurations): void {
        foreach ($exportConfigurations['actions'] as $index => $exportConfiguration) {
            if (!isset($exportConfiguration['data_entity'])) {
                throw new \Exception(sprintf('"data_entity" property is mandatory in action #%d', $index));
            }
        }
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
        $globalExportConfigurationDefaults = $this->dataExportService->readConfiguration($this->dataExportConfig->getExportConfigurationDefaultsPath());

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
