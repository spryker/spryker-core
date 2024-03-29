<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport\Mapper;

use Generated\Shared\Transfer\DataExportConfigurationsTransfer;

class DataExportConfigurationMapper implements DataExportConfigurationMapperInterface
{
    /**
     * @param array<string, mixed> $dataExportConfigurationData
     * @param \Generated\Shared\Transfer\DataExportConfigurationsTransfer $dataExportConfigurationsTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationsTransfer
     */
    public function mapDataExportConfigurationDataToDataExportConfigurationsTransfer(
        array $dataExportConfigurationData,
        DataExportConfigurationsTransfer $dataExportConfigurationsTransfer
    ): DataExportConfigurationsTransfer {
        return $dataExportConfigurationsTransfer->fromArray($dataExportConfigurationData, true);
    }
}
