<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport\Plugin\DataExport;

use Generated\Shared\Transfer\DataExportBatchTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportFormatResponseTransfer;
use Generated\Shared\Transfer\DataExportWriteResponseTransfer;
use Spryker\Service\DataExportExtension\Dependency\Plugin\DataExportConnectionPluginInterface;
use Spryker\Service\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Service\DataExport\DataExportServiceFactory getFactory()
 */
class OutputStreamDataExportConnectionPlugin extends AbstractPlugin implements DataExportConnectionPluginInterface
{
    /**
     * @var string
     */
    protected const CONNECTION_TYPE_OUTPUT_STREAM = 'output-stream';

    /**
     * {@inheritDoc}
     * - Requires `DataExportConfigurationTransfer.connection` to be provided.
     * - Requires `DataExportConfigurationTransfer.connection.type` to be provided.
     * - Returns `true` if provided connection type is `output-stream`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return bool
     */
    public function isApplicable(DataExportConfigurationTransfer $dataExportConfigurationTransfer): bool
    {
        return $dataExportConfigurationTransfer->getConnectionOrFail()->getTypeOrFail() === static::CONNECTION_TYPE_OUTPUT_STREAM;
    }

    /**
     * {@inheritDoc}
     * - Requires `DataExportConfigurationTransfer.destination` to be set.
     * - Writes formatted data batch to output stream.
     * - Returns `DataExportWriteResponseTransfer` with `isSuccessful=true` on success, otherwise with `isSuccessful=false` and error messages.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportFormatResponseTransfer $dataExportFormatResponseTransfer
     * @param \Generated\Shared\Transfer\DataExportBatchTransfer $dataExportBatchTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportWriteResponseTransfer
     */
    public function write(
        DataExportFormatResponseTransfer $dataExportFormatResponseTransfer,
        DataExportBatchTransfer $dataExportBatchTransfer,
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportWriteResponseTransfer {
        return $this->getFactory()
            ->createOutputStreamFormattedDataExportWriter()
            ->write(
                $dataExportFormatResponseTransfer,
                $dataExportBatchTransfer,
                $dataExportConfigurationTransfer,
            );
    }
}
