<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterQueueReaderConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Generated\Shared\Transfer\QueueDataImporterConfigurationTransfer;
use Spryker\Shared\DataImport\DataImportConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class DataImportConfig extends AbstractBundleConfig
{
    public const IMPORT_GROUP_FULL = 'FULL';
    public const IMPORT_GROUP_QUEUE_READERS = 'QUEUE_READERS';
    public const IMPORT_GROUP_QUEUE_WRITERS = 'QUEUE_WRITERS';

    protected const DEFAULT_QUEUE_READER_CHUNK_SIZE = 100;
    protected const DEFAULT_QUEUE_WRITER_CHUNK_SIZE = 100;

    /**
     * @return string
     */
    public function getDataImportRootPath()
    {
        $defaultPath = $this->getDefaultPath();
        $dataImportRootPath = $this->get(DataImportConstants::IMPORT_FILE_ROOT_PATH, $defaultPath);

        return rtrim($dataImportRootPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * @return int
     */
    public function getQueueReaderChunkSize(): int
    {
        return $this->get(DataImportConstants::QUEUE_READER_CHUNK_SIZE, static::DEFAULT_QUEUE_READER_CHUNK_SIZE);
    }

    /**
     * @return int
     */
    public function getQueueWriterChunkSize(): int
    {
        return $this->get(DataImportConstants::QUEUE_WRITER_CHUNK_SIZE, static::DEFAULT_QUEUE_WRITER_CHUNK_SIZE);
    }

    /**
     * @return string[]
     */
    public function getFullImportTypes(): array
    {
        return [];
    }

    /**
     * @param string $file
     * @param string $importType
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    protected function buildImporterConfiguration($file, $importType)
    {
        $dataImportReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImportReaderConfigurationTransfer
            ->setFileName($file)
            ->addDirectory($this->getDataImportRootPath());

        $dataImporterConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImporterConfigurationTransfer
            ->setImportType($importType)
            ->setReaderConfiguration($dataImportReaderConfigurationTransfer);

        return $dataImporterConfigurationTransfer;
    }

    /**
     * @param string $queueName
     * @param string $importType
     * @param array $queueConsumerOptions
     *
     * @return \Generated\Shared\Transfer\QueueDataImporterConfigurationTransfer
     */
    protected function buildQueueDataImporterConfiguration(string $queueName, string $importType, array $queueConsumerOptions): QueueDataImporterConfigurationTransfer
    {
        $dataImportQueueReaderConfigurationTransfer = new DataImporterQueueReaderConfigurationTransfer();
        $dataImportQueueReaderConfigurationTransfer
            ->setQueueName($queueName)
            ->setChunkSize(
                $this->getQueueReaderChunkSize()
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
