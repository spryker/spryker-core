<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Business\Exporter;

use Generated\Shared\Transfer\DataExportConfigurationsTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;
use Generator;
use Spryker\Service\DataExport\DataExportServiceInterface;
use Spryker\Zed\DataExport\Business\Exception\DataExporterNotFoundException;
use Spryker\Zed\DataExport\DataExportConfig;
use Spryker\Zed\DataExport\Dependency\Facade\DataExportToGracefulRunnerFacadeInterface;

class DataExportExecutor
{
    protected const HOOK_KEY_EXTENSION = 'extension';
    protected const HOOK_KEY_DATA_ENTITY = 'data_entity';

    /**
     * @var \Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityExporterPluginInterface[]
     */
    protected $dataEntityExporterPlugins = [];

    /**
     * @var \Spryker\Service\DataExport\DataExportServiceInterface
     */
    protected $dataExportService;

    /**
     * @var \Spryker\Zed\DataExport\DataExportConfig
     */
    protected $dataExportConfig;

    /**
     * @var \Spryker\Zed\DataExport\Dependency\Facade\DataExportToGracefulRunnerFacadeInterface
     */
    protected $gracefulRunnerFacade;

    /**
     * @param \Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityExporterPluginInterface[] $dataEntityExporterPlugins
     * @param \Spryker\Service\DataExport\DataExportServiceInterface $dataExportService
     * @param \Spryker\Zed\DataExport\DataExportConfig $dataExportConfig
     * @param \Spryker\Zed\DataExport\Dependency\Facade\DataExportToGracefulRunnerFacadeInterface $gracefulRunnerFacade
     */
    public function __construct(
        array $dataEntityExporterPlugins,
        DataExportServiceInterface $dataExportService,
        DataExportConfig $dataExportConfig,
        DataExportToGracefulRunnerFacadeInterface $gracefulRunnerFacade
    ) {
        $this->dataExportService = $dataExportService;
        $this->dataExportConfig = $dataExportConfig;
        $this->gracefulRunnerFacade = $gracefulRunnerFacade;

        foreach ($dataEntityExporterPlugins as $dataEntityExporterPlugin) {
            $this->dataEntityExporterPlugins[$dataEntityExporterPlugin::getDataEntity()] = $dataEntityExporterPlugin;
        }
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
        $dataExportDefaultsConfigurationTransfer = $this->dataExportService->mergeDataExportConfigurationTransfers(
            $dataExportConfigurationsTransfer->getDefaults() ?? new DataExportConfigurationTransfer(),
            $dataExportDefaultsConfigurationsTransfer->getDefaultsOrFail()
        );

        $this->gracefulRunnerFacade->run($this->runGraceful($dataExportConfigurationsTransfer, $dataExportDefaultsConfigurationTransfer));

        return $dataExportResultTransfers;
    }

    /**
     * This method is turned into a `\Generator` by using the `yield` operator. Every iteration of it will be fully
     * completed until a signal was received.
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationsTransfer $dataExportConfigurationsTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportDefaultsConfigurationTransfer
     *
     * @return \Generator
     */
    protected function runGraceful(
        DataExportConfigurationsTransfer $dataExportConfigurationsTransfer,
        DataExportConfigurationTransfer $dataExportDefaultsConfigurationTransfer
    ): Generator {
        foreach ($dataExportConfigurationsTransfer->getActions() as $dataExportConfigurationTransfer) {
            yield;

            $dataExportConfigurationTransfer = $this->dataExportService->mergeDataExportConfigurationTransfers(
                $dataExportConfigurationTransfer,
                clone $dataExportDefaultsConfigurationTransfer
            );
            $dataExportConfigurationTransfer = $this->addDataExportConfigurationActionHooks($dataExportConfigurationTransfer);

            $dataExportResultTransfers[] = $this->runExport($dataExportConfigurationTransfer);
        }
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
     * @throws \Spryker\Zed\DataExport\Business\Exception\DataExporterNotFoundException
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    protected function runExport(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer
    {
        $dataEntity = $dataExportConfigurationTransfer->getDataEntity();
        if (isset($this->dataEntityExporterPlugins[$dataEntity])) {
            return $this->dataEntityExporterPlugins[$dataEntity]->export($dataExportConfigurationTransfer);
        }

        throw new DataExporterNotFoundException(sprintf(
            'Data exporter not found for %s data entity',
            $dataExportConfigurationTransfer->getDataEntity()
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    protected function addDataExportConfigurationActionHooks(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportConfigurationTransfer
    {
        $dataExportConfigurationTransfer->addHook(static::HOOK_KEY_DATA_ENTITY, $dataExportConfigurationTransfer->getDataEntity());
        $dataExportConfigurationTransfer->addHook(
            static::HOOK_KEY_EXTENSION,
            $this->dataExportService->getFormatExtension($dataExportConfigurationTransfer)
        );

        return $dataExportConfigurationTransfer;
    }
}
