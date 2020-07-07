<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport\Writer;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportWriteResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Service\DataExport\DataExportConfig;
use Spryker\Service\DataExport\Formatter\DataExportFormatterInterface;
use Spryker\Service\DataExport\Resolver\DataExportPathResolverInterface;

class DataExportLocalWriter implements DataExportWriterInterface
{
    protected const ACCESS_MODE_TYPE_OVERWRITE = 'wb';
    protected const ACCESS_MODE_TYPE_APPEND = 'ab';

    protected const LOCAL_CONNECTION_PARAM_EXPORT_ROOT_DIR = 'export_root_dir';

    /**
     * @var \Spryker\Service\DataExport\Formatter\DataExportFormatterInterface
     */
    protected $dataExportFormatter;

    /**
     * @var \Spryker\Service\DataExport\Resolver\DataExportPathResolverInterface
     */
    protected $dataExportPathResolver;

    /**
     * @var \Spryker\Service\DataExport\DataExportConfig
     */
    protected $dataExportConfig;

    /**
     * @param \Spryker\Service\DataExport\Formatter\DataExportFormatterInterface $dataExportFormatter
     * @param \Spryker\Service\DataExport\Resolver\DataExportPathResolverInterface $dataExportPathResolver
     * @param \Spryker\Service\DataExport\DataExportConfig $dataExportConfig
     */
    public function __construct(
        DataExportFormatterInterface $dataExportFormatter,
        DataExportPathResolverInterface $dataExportPathResolver,
        DataExportConfig $dataExportConfig
    ) {
        $this->dataExportFormatter = $dataExportFormatter;
        $this->dataExportPathResolver = $dataExportPathResolver;
        $this->dataExportConfig = $dataExportConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportBatchTransfer $dataExportBatchTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportWriteResponseTransfer
     */
    public function write(
        DataExportBatchTransfer $dataExportBatchTransfer,
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportWriteResponseTransfer {
        $dataExportWriteResponseTransfer = $this->isValidConfiguration($dataExportConfigurationTransfer);
        if (!$dataExportWriteResponseTransfer->getIsSuccessful()) {
            return $dataExportWriteResponseTransfer;
        }

        $dataFormatResponseTransfer = $this->dataExportFormatter->formatBatch($dataExportBatchTransfer, $dataExportConfigurationTransfer);
        if (!$dataFormatResponseTransfer->getIsSuccessful()) {
            return $dataExportWriteResponseTransfer
                ->setIsSuccessful(false)
                ->setMessages($dataFormatResponseTransfer->getMessages());
        }

        $filePath = $this->dataExportPathResolver->resolvePath(
            $dataExportConfigurationTransfer,
            $dataExportConfigurationTransfer->getConnection()->getParams()[static::LOCAL_CONNECTION_PARAM_EXPORT_ROOT_DIR]
        );

        if (!$this->createDirectory($filePath)) {
            return $dataExportWriteResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage($this->createWriteFailErrorMessage($filePath));
        }

        $file = fopen($filePath, $dataExportBatchTransfer->getOffset() === 0 ? static::ACCESS_MODE_TYPE_OVERWRITE : static::ACCESS_MODE_TYPE_APPEND);
        if (!$file) {
            return $dataExportWriteResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage($this->createWriteFailErrorMessage($filePath));
        }

        $result = fwrite($file, $dataFormatResponseTransfer->getDataFormatted());
        if ($result === false) {
            return $dataExportWriteResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage($this->createWriteFailErrorMessage($filePath));
        }
        fclose($file);

        return $dataExportWriteResponseTransfer
            ->setFileName(basename($filePath));
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportWriteResponseTransfer
     */
    protected function isValidConfiguration(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportWriteResponseTransfer
    {
        $result = (new DataExportWriteResponseTransfer())
            ->setIsSuccessful(true);

        $params = $dataExportConfigurationTransfer->getConnection()->getParams();
        if (isset($params[static::LOCAL_CONNECTION_PARAM_EXPORT_ROOT_DIR])) {
            return $result;
        }

        return $result
            ->setIsSuccessful(false)
            ->addMessage($this->createConfigurationErrorMessage($dataExportConfigurationTransfer));
    }

    /**
     * @param string $filePath
     * @param int $permission
     *
     * @return bool
     */
    protected function createDirectory(string $filePath, int $permission = 0777): bool
    {
        $dirName = dirname($filePath);

        return is_dir($dirName) || mkdir($dirName, $permission, true) || is_dir($dirName);
    }

    /**
     * @param string|null $filePath
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createWriteFailErrorMessage(?string $filePath): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue(
                sprintf('Failed to write file "%s".', $filePath)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createConfigurationErrorMessage(DataExportConfigurationTransfer $dataExportConfigurationTransfer): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue(
                sprintf('Missing local connection parameter (export_root_dir) for data_entity "%s".', $dataExportConfigurationTransfer->getDataEntity())
            );
    }
}
