<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport\Merger;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;

class DataExportConfigurationMerger implements DataExportConfigurationMergerInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer|null $masterDataExportConfigurationTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer|null $slaveDataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    public function mergeDataExportConfigurationTransfers(
        ?DataExportConfigurationTransfer $masterDataExportConfigurationTransfer,
        ?DataExportConfigurationTransfer $slaveDataExportConfigurationTransfer
    ): DataExportConfigurationTransfer {
        if (!($masterDataExportConfigurationTransfer && $slaveDataExportConfigurationTransfer)) {
            return $masterDataExportConfigurationTransfer ?? $slaveDataExportConfigurationTransfer;
        }

        $masterDataExportConfigurationTransfer->setHooks(
            $this->mergeDataExportConfigurationHooks($masterDataExportConfigurationTransfer, $slaveDataExportConfigurationTransfer)
        );
        $masterDataExportConfigurationTransfer->setFilterCriteria(
            $this->mergeDataExportConfigurationFilterCriteria($masterDataExportConfigurationTransfer, $slaveDataExportConfigurationTransfer)
        );

        return $slaveDataExportConfigurationTransfer->fromArray($masterDataExportConfigurationTransfer->modifiedToArray());
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $masterDataExportConfigurationTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $slaveDataExportConfigurationTransfer
     *
     * @return array
     */
    protected function mergeDataExportConfigurationHooks(
        DataExportConfigurationTransfer $masterDataExportConfigurationTransfer,
        DataExportConfigurationTransfer $slaveDataExportConfigurationTransfer
    ): array {
        return array_merge(
            $slaveDataExportConfigurationTransfer->getHooks(),
            $masterDataExportConfigurationTransfer->getHooks()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $masterDataExportConfigurationTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $slaveDataExportConfigurationTransfer
     *
     * @return array
     */
    protected function mergeDataExportConfigurationFilterCriteria(
        DataExportConfigurationTransfer $masterDataExportConfigurationTransfer,
        DataExportConfigurationTransfer $slaveDataExportConfigurationTransfer
    ): array {
        return array_merge(
            $slaveDataExportConfigurationTransfer->getFilterCriteria(),
            $masterDataExportConfigurationTransfer->getFilterCriteria()
        );
    }
}
