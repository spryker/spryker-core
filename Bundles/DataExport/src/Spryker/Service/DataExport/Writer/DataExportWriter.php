<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport\Writer;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportWriteResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Service\DataExport\Formatter\DataExportFormatterInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

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
     * @param \Spryker\Service\DataExportExtension\Dependency\Plugin\DataExportConnectionPluginInterface[] $dataExportConnectionPlugins
     * @param \Spryker\Service\DataExport\Formatter\DataExportFormatterInterface $dataExportFormatter
     * @param \Spryker\Service\DataExport\Writer\DataExportWriterInterface $dataExportLocalWriter
     */
    public function __construct(
        array $dataExportConnectionPlugins,
        DataExportFormatterInterface $dataExportFormatter,
        DataExportWriterInterface $dataExportLocalWriter
    ) {
        $this->dataExportConnectionPlugins = $dataExportConnectionPlugins;
        $this->dataExportFormatter = $dataExportFormatter;
        $this->dataExportLocalWriter = $dataExportLocalWriter;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $writeConfiguration
     *
     * @return \Generated\Shared\Transfer\DataExportWriteResponseTransfer
     */
    public function write(
        array $data,
        DataExportConfigurationTransfer $dataExportConfigurationTransfer,
        AbstractTransfer $writeConfiguration
    ): DataExportWriteResponseTransfer {
        $dataExportConfigurationTransfer
            ->requireDestination()
            ->requireConnection();

        $dataExportWriteResponseTransfer = (new DataExportWriteResponseTransfer())
            ->setIsSuccessful(false);

        foreach ($this->dataExportConnectionPlugins as $dataExportConnectionPlugin) {
            if (!$dataExportConnectionPlugin->isApplicable($dataExportConfigurationTransfer)) {
                continue;
            }

            $dataFormatResponseTransfer = $this->dataExportFormatter->formatBatch($data, $dataExportConfigurationTransfer);
            if (!$dataFormatResponseTransfer->getIsSuccessful()) {
                return $dataExportWriteResponseTransfer->setMessages($dataFormatResponseTransfer->getMessages());
            }

            return $dataExportConnectionPlugin->write(
                $dataFormatResponseTransfer->getDataFormatted(),
                $dataExportConfigurationTransfer,
                $writeConfiguration
            );
        }

        $connectionType = $dataExportConfigurationTransfer->getConnection()->getType();
        if ($connectionType === static::CONNECTION_TYPE_LOCAL) {
            return $this->dataExportLocalWriter->write($data, $dataExportConfigurationTransfer, $writeConfiguration);
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
