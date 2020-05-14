<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport\Resolver;

use Generated\Shared\Transfer\DataExportConfigurationsTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Spryker\Service\DataExport\Merger\DataExportConfigurationMergerInterface;

class DataExportConfigurationResolver implements DataExportConfigurationResolverInterface
{
    /**
     * @var \Spryker\Service\DataExport\Merger\DataExportConfigurationMergerInterface
     */
    protected $dataExportConfigurationMerger;

    /**
     * @param \Spryker\Service\DataExport\Merger\DataExportConfigurationMergerInterface $dataExportConfigurationMerger
     */
    public function __construct(DataExportConfigurationMergerInterface $dataExportConfigurationMerger)
    {
        $this->dataExportConfigurationMerger = $dataExportConfigurationMerger;
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportActionConfigurationTransfer
     * @param \Generated\Shared\Transfer\DataExportConfigurationsTransfer $additionalDataExportConfigurationsTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    public function resolveDataExportActionConfiguration(
        DataExportConfigurationTransfer $dataExportActionConfigurationTransfer,
        DataExportConfigurationsTransfer $additionalDataExportConfigurationsTransfer
    ): DataExportConfigurationTransfer {
        $dataExportActionConfigurationTransfer = $this->dataExportConfigurationMerger->mergeDataExportConfigurationTransfers(
            $dataExportActionConfigurationTransfer,
            $additionalDataExportConfigurationsTransfer->getDefaults()
        );

        $additionalDataExportActionConfigurationTransfer = $this->findDataExportActionConfigurationByDataEntity(
            $dataExportActionConfigurationTransfer->getDataEntity(),
            $additionalDataExportConfigurationsTransfer
        );

        if (!$additionalDataExportActionConfigurationTransfer) {
            return $dataExportActionConfigurationTransfer;
        }

        return $this->dataExportConfigurationMerger->mergeDataExportConfigurationTransfers(
            $dataExportActionConfigurationTransfer,
            $additionalDataExportActionConfigurationTransfer
        );
    }

    /**
     * @param string $dataEntity
     * @param \Generated\Shared\Transfer\DataExportConfigurationsTransfer $dataExportConfigurationsTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer|null
     */
    protected function findDataExportActionConfigurationByDataEntity(
        string $dataEntity,
        DataExportConfigurationsTransfer $dataExportConfigurationsTransfer
    ): ?DataExportConfigurationTransfer {
        foreach ($dataExportConfigurationsTransfer->getActions() as $dataExportConfigurationTransfer) {
            if ($dataExportConfigurationTransfer->getDataEntity() === $dataEntity) {
                return $dataExportConfigurationTransfer;
            }
        }

        return null;
    }
}
