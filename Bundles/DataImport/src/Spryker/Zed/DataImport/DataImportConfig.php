<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport;

use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterQueueReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\QueueDataImporterConfigurationTransfer;
use Spryker\Shared\DataImport\DataImportConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class DataImportConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const IMPORT_GROUP_FULL = 'FULL';

    /**
     * @var string
     */
    public const IMPORT_GROUP_QUEUE_READERS = 'QUEUE_READERS';

    /**
     * @var string
     */
    public const IMPORT_GROUP_QUEUE_WRITERS = 'QUEUE_WRITERS';

    /**
     * @var string
     */
    public const ZED_DB_ENGINE = 'ZED_DB_ENGINE';

    /**
     * @var int
     */
    protected const DEFAULT_QUEUE_READER_CHUNK_SIZE = 100;

    /**
     * @var int
     */
    protected const DEFAULT_QUEUE_WRITER_CHUNK_SIZE = 100;

    /**
     * @var bool
     */
    protected const DEFAULT_BULK_MODE = false;

    /**
     * @var int
     */
    protected const BULK_MODE_GRADUALITY_FACTOR = 5;

    /**
     * @var int
     */
    protected const BULK_MODE_MEMORY_THRESHOLD_PERCENT = 30;

    /**
     * @api
     *
     * @return string
     */
    public function getDataImportRootPath()
    {
        $defaultPath = $this->getDefaultPath();
        $dataImportRootPath = $this->get(DataImportConstants::IMPORT_FILE_ROOT_PATH, $defaultPath);

        return rtrim($dataImportRootPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getQueueReaderChunkSize(): int
    {
        return $this->get(DataImportConstants::QUEUE_READER_CHUNK_SIZE, static::DEFAULT_QUEUE_READER_CHUNK_SIZE);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getQueueWriterChunkSize(): int
    {
        return $this->get(DataImportConstants::QUEUE_WRITER_CHUNK_SIZE, static::DEFAULT_QUEUE_WRITER_CHUNK_SIZE);
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getFullImportTypes(): array
    {
        return [];
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getDefaultYamlConfigPath(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @see \Spryker\Shared\Propel\PropelConstants::ZED_DB_ENGINE
     *
     * @return string|null
     */
    public function getCurrentDatabaseEngine(): ?string
    {
        return $this->get(static::ZED_DB_ENGINE);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isBulkEnabled(): bool
    {
        return $this->get(DataImportConstants::IS_BULK_MODE_ENABLED, static::DEFAULT_BULK_MODE);
    }

    /**
     * Specification:
     * - Returns graduality factor for increasing memory quota in bulk mode.
     *
     * @api
     *
     * @return int
     */
    public function getBulkWriteGradualityFactor(): int
    {
        return $this->get(DataImportConstants::BULK_MODE_GRADUALITY_FACTOR, static::BULK_MODE_GRADUALITY_FACTOR);
    }

    /**
     * Specification:
     * - Returns memory threshold limit in percentage of total allowed memory.
     *
     * @api
     *
     * @return int
     */
    public function getBulkWriteMemoryThresholdPercent(): int
    {
        return $this->get(
            DataImportConstants::BULK_MODE_MEMORY_THESHOLD_PERCENT,
            $this->get(DataImportConstants::BULK_MODE_MEMORY_THRESHOLD_PERCENT, static::BULK_MODE_MEMORY_THRESHOLD_PERCENT),
        );
    }

    /**
     * Specification:
     * - Returns memory threshold limit in percentage of total allowed memory.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\DataImport\DataImportConfig::getBulkWriteMemoryThresholdPercent()} instead.
     *
     * @return int
     */
    public function getBulkWriteMemoryThesoldPercent(): int
    {
        return $this->get(DataImportConstants::BULK_MODE_MEMORY_THESHOLD_PERCENT, static::BULK_MODE_MEMORY_THRESHOLD_PERCENT);
    }

    /**
     * @param string $file
     * @param string $importType
     * @param \Generated\Shared\Transfer\DataImporterConfigurationContextTransfer|null $context
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    protected function buildImporterConfiguration($file, $importType, $context = null)
    {
        $dataImportReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImportReaderConfigurationTransfer
            ->setFileName($file)
            ->addDirectory($this->getDataImportRootPath());

        $dataImporterConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImporterConfigurationTransfer
            ->setImportType($importType)
            ->setReaderConfiguration($dataImportReaderConfigurationTransfer)
            ->setContext($context);

        return $dataImporterConfigurationTransfer;
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function buildImporterConfigurationByDataImportConfigAction(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
    ): DataImporterConfigurationTransfer {
        $dataImportReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImportReaderConfigurationTransfer
            ->setFileName($dataImportConfigurationActionTransfer->getSource())
            ->setFileSystem($dataImportConfigurationActionTransfer->getFilesystem());

        $dataImporterConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImporterConfigurationTransfer
            ->setImportType($dataImportConfigurationActionTransfer->getDataEntity())
            ->setReaderConfiguration($dataImportReaderConfigurationTransfer)
            ->setContext($dataImportConfigurationActionTransfer->getContext());

        return $dataImporterConfigurationTransfer;
    }

    /**
     * Specification:
     * - Returns true if data import from other source is enabled.
     *
     * @api
     *
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @return bool
     */
    public function isDataImportFromOtherSourceEnabled(): bool
    {
        return false;
    }

    /**
     * @param string $queueName
     * @param string $importType
     * @param array $queueConsumerOptions
     *
     * @return \Generated\Shared\Transfer\QueueDataImporterConfigurationTransfer
     */
    protected function buildQueueDataImporterConfiguration(
        string $queueName,
        string $importType,
        array $queueConsumerOptions
    ): QueueDataImporterConfigurationTransfer {
        $dataImportQueueReaderConfigurationTransfer = new DataImporterQueueReaderConfigurationTransfer();
        $dataImportQueueReaderConfigurationTransfer
            ->setQueueName($queueName)
            ->setChunkSize(
                $this->getQueueReaderChunkSize(),
            )
            ->setQueueConsumerOptions($queueConsumerOptions);

        $queueDataImporterConfigurationTransfer = new QueueDataImporterConfigurationTransfer();
        $queueDataImporterConfigurationTransfer
            ->setImportType($importType)
            ->setReaderConfiguration($dataImportQueueReaderConfigurationTransfer);

        return $queueDataImporterConfigurationTransfer;
    }

    /**
     * @return string
     */
    private function getDefaultPath()
    {
        $pathParts = [
            APPLICATION_ROOT_DIR,
            'data',
            'import',
        ];

        return implode(DIRECTORY_SEPARATOR, $pathParts) . DIRECTORY_SEPARATOR;
    }
}
