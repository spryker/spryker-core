<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExportExtension\Dependency\Plugin;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Generated\Shared\Transfer\DataExportWriteResponseTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface DataExportConnectionPluginInterface
{
    /**
     * Specification:
     * - TODO
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return bool
     */
    public function isApplicable(DataExportConfigurationTransfer $dataExportConfigurationTransfer): bool;

    /**
     * Specification:
     * - TODO
     *
     * @api
     *
     * @param string $data
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $writeConfiguration
     *
     * @return \Generated\Shared\Transfer\DataExportWriteResponseTransfer
     */
    public function write(
        string $data,
        DataExportConfigurationTransfer $dataExportConfigurationTransfer,
        AbstractTransfer $writeConfiguration
    ): DataExportWriteResponseTransfer;
}
