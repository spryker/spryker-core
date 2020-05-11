<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Business\ConfigurationResolver;

use Generated\Shared\Transfer\DataExportConfigurationTransfer;

interface SalesDataExportConfigurationResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationTransfer $dataExportConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationTransfer
     */
    public function resolveSalesDataExportActionConfiguration(DataExportConfigurationTransfer $dataExportConfigurationTransfer): DataExportConfigurationTransfer;
}
