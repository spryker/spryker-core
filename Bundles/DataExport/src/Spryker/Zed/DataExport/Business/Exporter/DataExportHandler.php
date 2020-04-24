<?php

namespace Spryker\Zed\DataExport\Business\Exporter;

use Generated\Shared\Transfer\DataExportReportTransfer;
use Spryker\Service\DataExport\DataExportService;
use Spryker\Service\UtilDataReader\UtilDataReaderService;
use Spryker\Zed\DataExport\DataExportConfig;
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

    /** @var DataExportService */
    protected $dataExportService;

    /**
     * @var DataExportConfig
     */
    protected $dataExportConfig;

    public function __construct(array $dataEntityExporterPlugins, array $dataExportConnectionPlugins, array $dataExportWriterPlugins, DataExportService $dataExportService, DataExportConfig $dataExportConfig)
    {
        $this->dataEntityExporterPlugins = $dataEntityExporterPlugins;
        $this->dataExportConnectionPlugins = $dataExportConnectionPlugins;
        $this->dataExportWriterPlugins = $dataExportWriterPlugins;
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
        $exportConfigurationDefaults = $this->dataExportService->readConfiguration($this->dataExportConfig->getExportConfigurationDefaultsPath());
        $exportConfigurations = $this->applyExportConfigurationDefaults($exportConfigurations, $exportConfigurationDefaults);
        $this->assertExportConfigurationBatch($exportConfigurations);

        $dataExportResultTransfer = (new DataExportReportTransfer())
            ->setIsSuccess(true);

        foreach($exportConfigurations['actions'] as $exportConfiguration) {
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
     * @param array $exportConfigurationBatch
     * @param array $exportConfigurationDefaults
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function applyExportConfigurationDefaults(array $exportConfigurationBatch, array $exportConfigurationDefaults): array {
        $exportConfigurationBatch['defaults'] = ($exportConfigurationBatch['defaults'] ?? []) + ($exportConfigurationDefaults['defaults'] ?? []);
        foreach($exportConfigurationBatch['actions'] as &$exportConfiguration) {
            $exportConfiguration += $exportConfigurationBatch['defaults'];
            $exportConfiguration['hidden'] = [
                'timestamp' => time(),
                'application_root_dir' => APPLICATION_ROOT_DIR,
                'extension' => $this->getWriterExtension($exportConfiguration),
            ];
        }

        return $exportConfigurationBatch;
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
