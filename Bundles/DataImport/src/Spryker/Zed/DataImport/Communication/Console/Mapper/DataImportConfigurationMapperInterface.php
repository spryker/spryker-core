<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication\Console\Mapper;

use Generated\Shared\Transfer\DataImportConfigurationTransfer;

interface DataImportConfigurationMapperInterface
{
    /**
     * @param array $dataImportConfigurationData
     * @param \Generated\Shared\Transfer\DataImportConfigurationTransfer $dataImportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportConfigurationTransfer
     */
    public function mapDataImportConfigurationDataToDataImportConfigurationTransfer(
        array $dataImportConfigurationData,
        DataImportConfigurationTransfer $dataImportConfigurationTransfer
    ): DataImportConfigurationTransfer;
}
