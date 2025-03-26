<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Business\Exporter;

use Exception;
use Generated\Shared\Transfer\DataExportConfigurationsTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;
use Generator;
use Spryker\Service\DataExport\DataExportServiceInterface;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\DataExport\Business\DataEntityPluginProvider\DataExportPluginProviderInterface;
use Spryker\Zed\DataExport\Business\Exception\DataExporterConfigException;
use Spryker\Zed\DataExport\DataExportConfig;
use Spryker\Zed\DataExport\Dependency\Facade\DataExportToGracefulRunnerFacadeInterface;
use Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityExporterPluginInterface;
use Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityFieldsConfigPluginInterface;
use Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityGeneratorPluginInterface;
use Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityReaderPluginInterface;
use Throwable;

class DataExportExecutor
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const FIELD_DELIMITER = ':';

    /**
     * @var string
     */
    protected const HOOK_KEY_EXTENSION = 'extension';

    /**
     * @var string
     */
    protected const HOOK_KEY_DATA_ENTITY = 'data_entity';

    /**
     * @var int
     */
    protected const DEFAULT_BATCH_SIZE = 1000;

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
     * @var \Spryker\Zed\DataExport\Business\Exporter\DataExportGeneratorExporterInterface
     */
    protected DataExportGeneratorExporterInterface $dataExportGeneratorExporter;

    /**
     * @var \Spryker\Zed\DataExport\Business\DataEntityPluginProvider\DataExportPluginProviderInterface
     */
    protected $dataExportPluginProvider;

    /**
     * @param \Spryker\Zed\DataExport\Business\DataEntityPluginProvider\DataExportPluginProviderInterface $dataExportPluginProvider
     * @param \Spryker\Service\DataExport\DataExportServiceInterface $dataExportService
     * @param \Spryker\Zed\DataExport\DataExportConfig $dataExportConfig
     * @param \Spryker\Zed\DataExport\Dependency\Facade\DataExportToGracefulRunnerFacadeInterface $gracefulRunnerFacade
     * @param \Spryker\Zed\DataExport\Business\Exporter\DataExportGeneratorExporterInterface $dataExportGeneratorExporter
     */
    public function __construct(
        DataExportPluginProviderInterface $dataExportPluginProvider,
        DataExportServiceInterface $dataExportService,
        DataExportConfig $dataExportConfig,
        DataExportToGracefulRunnerFacadeInterface $gracefulRunnerFacade,
        DataExportGeneratorExporterInterface $dataExportGeneratorExporter
    ) {
        $this->dataExportService = $dataExportService;
        $this->dataExportConfig = $dataExportConfig;
        $this->gracefulRunnerFacade = $gracefulRunnerFacade;
        $this->dataExportGeneratorExporter = $dataExportGeneratorExporter;
        $this->dataExportPluginProvider = $dataExportPluginProvider;
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationsTransfer $dataExportConfigurationsTransfer
     *
     * @return array<\Generated\Shared\Transfer\DataExportReportTransfer>
     */
    public function exportDataEntities(DataExportConfigurationsTransfer $dataExportConfigurationsTransfer): array
    {
        $dataExportDefaultsConfigurationsTransfer = $this->getDataExportDefaultsConfiguration();
        $dataExportDefaultsConfigurationTransfer = $this->dataExportService->mergeDataExportConfigurationTransfers(
            $dataExportConfigurationsTransfer->getDefaults() ?? new DataExportConfigurationTransfer(),
            $dataExportDefaultsConfigurationsTransfer->getDefaultsOrFail(),
        );

        $dataExportGenerator = $this->createDataExportGenerator($dataExportConfigurationsTransfer, $dataExportDefaultsConfigurationTransfer);

        $this->gracefulRunnerFacade->run($dataExportGenerator, Exception::class);

        return $dataExportGenerator->getReturn();
    }

    /**
     * This method is turned into a `\Generator` by using the `yield` operator. Every iteration of it will be fully
     * completed until a signal was received.
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationsTransfer $dataExportConfigurationsTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportDefaultsConfigurationTransfer
     *
     * @throws \Throwable
     *
     * @return \Generator<\Generated\Shared\Transfer\DataExportReportTransfer|null>
     */
    protected function createDataExportGenerator(
        DataExportConfigurationsTransfer $dataExportConfigurationsTransfer,
        DataExportConfigurationTransfer $dataExportDefaultsConfigurationTransfer
    ): Generator {
        $dataExportResultTransfers = [];

        try {
            foreach ($dataExportConfigurationsTransfer->getActions() as $dataExportConfigurationTransfer) {
                yield;

                $dataExportConfigurationTransfer = $this->dataExportService->mergeDataExportConfigurationTransfers(
                    $dataExportConfigurationTransfer,
                    clone $dataExportDefaultsConfigurationTransfer,
                );
                $dataExportConfigurationTransfer = $this->addDataExportConfigurationActionHooks($dataExportConfigurationTransfer);

                $dataExportResultTransfers[] = $this->runExport($dataExportConfigurationTransfer);
            }
        } catch (Throwable $throwable) {
            $this->getLogger()->error($throwable->getMessage(), ['exception' => $throwable]);

            if ($dataExportConfigurationsTransfer->getThrowException()) {
                throw $throwable;
            }
        }

        return $dataExportResultTransfers;
    }

    /**
     * @return \Generated\Shared\Transfer\DataExportConfigurationsTransfer
     */
    protected function getDataExportDefaultsConfiguration(): DataExportConfigurationsTransfer
    {
        return $this->dataExportService->parseConfiguration(
            $this->dataExportConfig->getExportConfigurationDefaultsPath(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @throws \Spryker\Zed\DataExport\Business\Exception\DataExporterConfigException
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    protected function runExport(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportReportTransfer
    {
        $dataEntityName = $dataExportConfigurationTransfer->getDataEntity();

        if (!$dataEntityName) {
            throw new DataExporterConfigException('No data entity name provided');
        }

        $this->dataExportPluginProvider->requireDataEntityPlugin($dataEntityName);
        $dataExportConfigurationTransfer = $this->expandDataExportConfigurationWithDataEntityPlugins($dataExportConfigurationTransfer);

        if ($this->dataExportPluginProvider->hasDataEntityPlugin($dataEntityName, DataEntityExporterPluginInterface::class)) {
            /** @phpstan-ignore-next-line method.notFound */
            return $this->dataExportPluginProvider
                ->getDataEntityPluginForInterface($dataEntityName, DataEntityExporterPluginInterface::class)
                ->export($dataExportConfigurationTransfer);
        }

        return $this->dataExportGeneratorExporter
            ->exportFromGenerator($this->getBatchGenerator($dataExportConfigurationTransfer), $dataExportConfigurationTransfer);
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
            $this->dataExportService->getFormatExtension($dataExportConfigurationTransfer),
        );

        return $dataExportConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @throws \Spryker\Zed\DataExport\Business\Exception\DataExporterConfigException
     *
     * @return \Generator<\Generated\Shared\Transfer\DataExportBatchTransfer>
     */
    protected function getBatchGenerator(DataExportConfigurationTransfer $dataExportConfigurationTransfer): Generator
    {
        $dataEntityName = $dataExportConfigurationTransfer->getDataEntity();

        if (!$dataEntityName) {
            throw new DataExporterConfigException('No data entity proveded');
        }

        if ($this->dataExportPluginProvider->hasDataEntityPlugin($dataEntityName, DataEntityGeneratorPluginInterface::class)) {
            /** @phpstan-ignore-next-line method.notFound */
            return $this->dataExportPluginProvider
                ->getDataEntityPluginForInterface($dataEntityName, DataEntityGeneratorPluginInterface::class)
                ->getBatchGenerator($dataExportConfigurationTransfer);
        }

        $offset = 0;
        $limit = $dataExportConfigurationTransfer->getBatchSize() ?: static::DEFAULT_BATCH_SIZE;
        $plugin = $this->dataExportPluginProvider->getDataEntityPluginForInterface($dataEntityName, DataEntityReaderPluginInterface::class);

        do {
            $dataExportConfigurationTransfer->setOffset($offset);
            /** @phpstan-ignore-next-line method.notFound */
            $dataExportBatchTransfer = $plugin->getDataBatch($dataExportConfigurationTransfer);
            $dataExportBatchTransfer->setOffset($offset)->setLimit($limit);

            yield $dataExportBatchTransfer;

            $offset += count($dataExportBatchTransfer->getData());
        } while (count($dataExportBatchTransfer->getData()) === $limit);
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @throws \Spryker\Zed\DataExport\Business\Exception\DataExporterConfigException
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    protected function expandDataExportConfigurationWithDataEntityPlugins(
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportConfigurationTransfer {
        $dataEntityName = $dataExportConfigurationTransfer->getDataEntity();

        if (!$dataEntityName) {
            return $dataExportConfigurationTransfer;
        }

        $dataEntityPlugin = $this->dataExportPluginProvider->findDataEntityPlugin($dataEntityName);

        if (!$dataEntityPlugin instanceof DataEntityFieldsConfigPluginInterface) {
            return $dataExportConfigurationTransfer;
        }

        $rawFields = array_merge($dataEntityPlugin->getFieldsConfig(), $dataExportConfigurationTransfer->getFields());

        if (!$rawFields) {
            throw new DataExporterConfigException('Please provide a list of fields in your data export configuration');
        }

        $fields = [];

        foreach ($rawFields as $key => $field) {
            if (!is_int($key) && !str_contains(static::FIELD_DELIMITER, $field)) {
                $fields[$key] = $key . static::FIELD_DELIMITER . $field;

                continue;
            }

            /** @phpstan-ignore-next-line argument.type */
            $exploded = explode(static::FIELD_DELIMITER, $field);
            $fields[$exploded[0]] = $field;
        }

        return $dataExportConfigurationTransfer->setFields(array_values($fields));
    }
}
