<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Business\Exporter;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;
use Generator;

interface DataExportGeneratorExporterInterface
{
    /**
     * @param \Generator<\Generated\Shared\Transfer\DataExportBatchTransfer> $dataExportGenerator
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     */
    public function exportFromGenerator(
        Generator $dataExportGenerator,
        DataExportConfigurationTransfer $dataExportConfigurationTransfer
    ): DataExportReportTransfer;
}
