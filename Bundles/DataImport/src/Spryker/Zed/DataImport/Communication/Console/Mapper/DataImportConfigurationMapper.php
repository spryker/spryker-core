<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication\Console\Mapper;

use Generated\Shared\Transfer\DataImportConfigurationTransfer;

class DataImportConfigurationMapper implements DataImportConfigurationMapperInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\DataImportConfigurationTransfer[] $dataImportConfigurationTransfers
     *
     * @return \Generated\Shared\Transfer\DataImportConfigurationTransfer[]
     */
    public function mapDataImportConfigurationDataToDataImportConfigurationTransfers(array $data, array $dataImportConfigurationTransfers): array
    {
        foreach ($data as $datum) {
            $dataImportConfigurationTransfers[] = $this->mapDataImportConfigurationDatumToDataImportConfigurationTransfer(
                $datum,
                new DataImportConfigurationTransfer()
            );
        }

        return $dataImportConfigurationTransfers;
    }

    /**
     * @param array $datum
     * @param \Generated\Shared\Transfer\DataImportConfigurationTransfer $dataImportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportConfigurationTransfer
     */
    protected function mapDataImportConfigurationDatumToDataImportConfigurationTransfer(
        array $datum,
        DataImportConfigurationTransfer $dataImportConfigurationTransfer
    ): DataImportConfigurationTransfer {
        return $dataImportConfigurationTransfer->fromArray($datum, true);
    }
}
