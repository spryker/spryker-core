<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Dependency\Facade;

use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;

interface FileImportMerchantPortalGuiToDataImportFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataImporterReportTransfer
     */
    public function importByAction(
        DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer,
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ): DataImporterReportTransfer;
}
