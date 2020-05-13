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
use Spryker\Service\DataExport\Formatter\DataExportFormatterInterface;
use Spryker\Service\DataExport\Resolver\DataExportPathResolverInterface;

class DataExportWriter implements DataExportWriterInterface
{
    protected const MESSAGE_CONNECTION_PLUGIN_NOT_FOUND = 'Connection plugin not found for format "%s"';
    protected const CONNECTION_TYPE_LOCAL = 'local';

    /**
     * @var \Spryker\Service\DataExportExtension\Dependency\Plugin\DataExportConnectionPluginInterface[]
     */
    protected $dataExportConnectionPlugins;

    /**
     * @var \Spryker\Service\DataExport\Formatter\DataExportFormatterInterface
     */
    protected $dataExportFormatter;

    /**
     * @var \Spryker\Service\DataExport\Writer\DataExportWriterInterface
     */
    protected $dataExportLocalWriter;

    /**
     * @var \Spryker\Service\DataExport\Resolver\DataExportPathResolverInterface
     */
    protected $dataExportPathResolver;

    /**
     * @param \Spryker\Service\DataExportExtension\Dependency\Plugin\DataExportConnectionPluginInterface[] $dataExportConnectionPlugins
     * @param \Spryker\Service\DataExport\Formatter\DataExportFormatterInterface $dataExportFormatter
     * @param \Spryker\Service\DataExport\Writer\DataExportWriterInterface $dataExportLocalWriter
     * @param \Spryker\Service\DataExport\Resolver\DataExportPathResolverInterface $dataExportPathResolver
     */
    public function __construct(
        array $dataExportConnectionPlugins,
        DataExportFormatterInterface $dataExportFormatter,
        DataExportWriterInterface $dataExportLocalWriter,
        DataExportPathResolverInterface $dataExportPathResolver
    ) {
        $this->dataExportConnectionPlugins = $dataExportConnectionPlugins;
        $this->dataExportFormatter = $dataExportFormatter;
        $this->dataExportLocalWriter = $dataExportLocalWriter;
        $this->dataExportPathResolver = $dataExportPathResolver;
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
        $dataExportConfigurationTransfer
            ->requireDestination()
            ->requireConnection();

        $dataExportWriteResponseTransfer = (new DataExportWriteResponseTransfer())
            ->setIsSuccessful(false);

        $dataExportConfigurationTransfer = $this->dataExportPathResolver->resolvePath($dataExportConfigurationTransfer);
        foreach ($this->dataExportConnectionPlugins as $dataExportConnectionPlugin) {
            if (!$dataExportConnectionPlugin->isApplicable($dataExportConfigurationTransfer)) {
                continue;
            }

            $dataFormatResponseTransfer = $this->dataExportFormatter->formatBatch($dataExportBatchTransfer, $dataExportConfigurationTransfer);
            if (!$dataFormatResponseTransfer->getIsSuccessful()) {
                return $dataExportWriteResponseTransfer->setMessages($dataFormatResponseTransfer->getMessages());
            }

            return $dataExportConnectionPlugin->write(
                $dataFormatResponseTransfer->getDataFormatted(),
                $dataExportBatchTransfer,
                $dataExportConfigurationTransfer
            );
        }

        $connectionType = $dataExportConfigurationTransfer->getConnection()->getType();
        if ($connectionType === static::CONNECTION_TYPE_LOCAL) {
            return $this->dataExportLocalWriter->write($dataExportBatchTransfer, $dataExportConfigurationTransfer);
        }

        return $dataExportWriteResponseTransfer->addMessage($this->createConnectionPluginNotFoundMessage($connectionType));
    }

    /**
     * @param string $connectionType
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createConnectionPluginNotFoundMessage(string $connectionType): MessageTransfer
    {
        return (new MessageTransfer())->setValue(
            sprintf(static::MESSAGE_CONNECTION_PLUGIN_NOT_FOUND, $connectionType)
        );
    }
}
