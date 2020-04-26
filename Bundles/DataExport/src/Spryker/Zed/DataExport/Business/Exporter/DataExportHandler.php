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
     * @return DataExportReportTransfer
     *@throws \Exception
     *
     */
    public function exportBatch(array $exportConfigurations): DataExportReportTransfer {
        $dataExportResultTransfer = (new DataExportReportTransfer())
            ->setIsSuccess(true);

        $this->assertExportConfigurationBatch($exportConfigurations);

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
     * @param array $exportConfigurationBatch
     *
     * @return void
     *@throws \Exception
     *
     */
    protected function assertExportConfigurationBatch(array $exportConfigurationBatch): void {
        foreach ($exportConfigurationBatch['actions'] as $index => $exportConfiguration) {
            if (!isset($exportConfiguration['data_entity'])) {
                throw new \Exception(sprintf('"data_entity" property is missing in action #%d', $index));
            }
        }
    }

    /**
     * @param array $exportConfigurations
     *
     * @return array
     *@throws \Exception
     *
     */
    protected function applyGlobalDataExportConfigurationsAdjuster(array $exportConfigurations): array {
        $exportConfigurationDefaults = $this->dataExportService->readConfiguration($this->dataExportConfig->getExportConfigurationDefaultsPath());

        $exportConfigurations['defaults'] = ($exportConfigurations['defaults'] ?? []) + ($exportConfigurationDefaults['defaults'] ?? []);
        foreach($exportConfigurations['actions'] as &$exportConfiguration) {
            $exportConfiguration += $exportConfigurations['defaults'];
            $exportConfiguration['hidden'] = [
                'timestamp' => time(),
                'application_root_dir' => APPLICATION_ROOT_DIR,
                'extension' => $this->getWriterExtension($exportConfiguration),
            ];
        }

        return $exportConfigurations;
    }

    /**
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

        // in-built fallback
        if ($exportConfiguration['writer']['type'] === 'csv') {
            return 'csv';
        }

        throw new \Exception('No writer is configured');
    }
}
