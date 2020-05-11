<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport\Merger;

use Generated\Shared\Transfer\DataExportConfigurationsTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;

class DataExportConfigurationMerger implements DataExportConfigurationMergerInterface
{
    public function mergeDataExportConfigurationsTransfers(
        DataExportConfigurationsTransfer $masterDataExportConfigurationsTransfer,
        DataExportConfigurationsTransfer $slaveDataExportConfigurationsTransfer
    ): DataExportConfigurationsTransfer {
        $dataExportConfigurationsTransfer = new DataExportConfigurationsTransfer();
        $dataExportConfigurationsTransfer->setVersion($masterDataExportConfigurationsTransfer->getVersion());

        $defaultsDataExportConfigurationTransfer = $this->mergeDataExportConfigurationTransfers(
            $masterDataExportConfigurationsTransfer->getDefaults(),
            $slaveDataExportConfigurationsTransfer->getDefaults()
        );
        $dataExportConfigurationsTransfer->setDefaults($defaultsDataExportConfigurationTransfer);

        $masterDataEntities = array_map(function (DataExportConfigurationTransfer $dataExportConfigurationTransfer): string {
            return $dataExportConfigurationTransfer->getDataEntity();
        }, $masterDataExportConfigurationsTransfer->getActions());
        $slaveDataEntities = array_map(function (DataExportConfigurationTransfer $dataExportConfigurationTransfer): string {
            return $dataExportConfigurationTransfer->getDataEntity();
        }, $slaveDataExportConfigurationsTransfer->getActions());

        $intersect = array_intersect($masterDataEntities, $slaveDataEntities);


        $dataExportConfigurationActionTransfers = [];
        foreach ($masterDataExportConfigurationsTransfer->getActions() as $masterDataExportConfigurationTransfer) {
            if (!in_array($masterDataExportConfigurationTransfer->getDataEntity(), $intersect, true)) {
                $dataExportConfigurationActionTransfers[] = $masterDataExportConfigurationTransfer;
            }
            foreach ($slaveDataExportConfigurationsTransfer->getActions() as $slaveDataExportConfigurationTransfer) {
                if ($masterDataExportConfigurationTransfer->getDataEntity() === $slaveDataExportConfigurationTransfer->getDataEntity()) {
                    $dataExportConfigurationActionTransfers[$masterDataExportConfigurationTransfer->getDataEntity()] = $this->mergeDataExportConfigurationsTransfers(
                        $masterDataExportConfigurationTransfer,
                        $slaveDataExportConfigurationTransfer
                    );

                    break;
                }
            }
        }
    }

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

        return $slaveDataExportConfigurationTransfer->fromArray($masterDataExportConfigurationTransfer->toArray());
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
}
