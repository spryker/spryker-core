<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication\Console\Mapper;

interface DataImportConfigurationMapperInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\DataImportConfigurationTransfer[] $dataImportConfigurationTransfers
     *
     * @return \Generated\Shared\Transfer\DataImportConfigurationTransfer[]
     */
    public function mapDataImportConfigurationDataToDataImportConfigurationTransfers(array $data, array $dataImportConfigurationTransfers): array;
}
