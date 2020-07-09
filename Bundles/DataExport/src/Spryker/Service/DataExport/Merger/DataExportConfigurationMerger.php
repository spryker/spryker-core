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
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer|null $primaryDataExportConfigurationTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer|null $secondaryDataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    public function mergeDataExportConfigurationTransfers(
        ?DataExportConfigurationTransfer $primaryDataExportConfigurationTransfer,
        ?DataExportConfigurationTransfer $secondaryDataExportConfigurationTransfer
    ): DataExportConfigurationTransfer {
        if (!($primaryDataExportConfigurationTransfer && $secondaryDataExportConfigurationTransfer)) {
            return $primaryDataExportConfigurationTransfer ?? $secondaryDataExportConfigurationTransfer;
        }

        $primaryDataExportConfigurationTransfer->setHooks(
            $this->mergeDataExportConfigurationHooks($primaryDataExportConfigurationTransfer, $secondaryDataExportConfigurationTransfer)
        );
        $primaryDataExportConfigurationTransfer->setFilterCriteria(
            $this->mergeDataExportConfigurationFilterCriteria($primaryDataExportConfigurationTransfer, $secondaryDataExportConfigurationTransfer)
        );

        return $secondaryDataExportConfigurationTransfer->fromArray($primaryDataExportConfigurationTransfer->modifiedToArray());
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $primaryDataExportConfigurationTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $secondaryDataExportConfigurationTransfer
     *
     * @return array
     */
    protected function mergeDataExportConfigurationHooks(
        DataExportConfigurationTransfer $primaryDataExportConfigurationTransfer,
        DataExportConfigurationTransfer $secondaryDataExportConfigurationTransfer
    ): array {
        return array_merge(
            $secondaryDataExportConfigurationTransfer->getHooks(),
            $primaryDataExportConfigurationTransfer->getHooks()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $primaryDataExportConfigurationTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $secondaryDataExportConfigurationTransfer
     *
     * @return array
     */
    protected function mergeDataExportConfigurationFilterCriteria(
        DataExportConfigurationTransfer $primaryDataExportConfigurationTransfer,
        DataExportConfigurationTransfer $secondaryDataExportConfigurationTransfer
    ): array {
        return array_merge(
            $secondaryDataExportConfigurationTransfer->getFilterCriteria(),
            $primaryDataExportConfigurationTransfer->getFilterCriteria()
        );
    }
}
