<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Business;

use Generated\Shared\Transfer\DataExportConfigurationsTransfer;
use Generated\Shared\Transfer\DataExportReportTransfer;

interface DataExportFacadeInterface
{
    /**
     * Specification:
     * - Todo
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationsTransfer $dataExportConfigurationsTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer[]
     *@api
     *
     */
    public function exportDataEntities(DataExportConfigurationsTransfer $dataExportConfigurationsTransfer): array;
}
