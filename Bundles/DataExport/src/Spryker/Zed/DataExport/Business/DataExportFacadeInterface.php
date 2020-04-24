<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Business;

use Generated\Shared\Transfer\DataExportReportTransfer;
use Generated\Shared\Transfer\DataExportResultTransfer;

interface DataExportFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @param array $exportConfigurations
     *
     * @return \Generated\Shared\Transfer\DataExportReportTransfer
     *@api
     *
     */
    public function exportBatch(array $exportConfigurations): DataExportReportTransfer;
}
