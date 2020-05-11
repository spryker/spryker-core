<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport\Merger;

use Generated\Shared\Transfer\DataExportConfigurationsTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;

interface DataExportConfigurationMergerInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationsTransfer $masterDataExportConfigurationsTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationsTransfer $slaveDataExportConfigurationsTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationsTransfer
     */
    public function mergeDataExportConfigurationsTransfers(
        DataExportConfigurationsTransfer $masterDataExportConfigurationsTransfer,
        DataExportConfigurationsTransfer $slaveDataExportConfigurationsTransfer
    ): DataExportConfigurationsTransfer;

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer|null $masterDataExportConfigurationTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer|null $slaveDataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    public function mergeDataExportConfigurationTransfers(
        ?DataExportConfigurationTransfer $masterDataExportConfigurationTransfer,
        ?DataExportConfigurationTransfer $slaveDataExportConfigurationTransfer
    ): DataExportConfigurationTransfer;
}
