<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Dependency\Plugin;

use Generated\Shared\Transfer\DataExportResultTransfer;

interface DataEntityExporterPluginInterface
{
    /**
     * @param array $exportConfiguration
     *
     * @return bool
     */
    public function isApplicable(array $exportConfiguration): bool;

    /**
     * @api
     *
     * @param array $exportConfiguration
     *
     * @return DataExportResultTransfer
     */
    public function exportBatch(array $exportConfiguration): DataExportResultTransfer;
}
