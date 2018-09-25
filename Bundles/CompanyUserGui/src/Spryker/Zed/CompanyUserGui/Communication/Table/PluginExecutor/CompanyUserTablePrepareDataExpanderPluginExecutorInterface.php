<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor;

use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyUserTablePrepareDataExpanderPluginExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return array
     */
    public function executePrepareDataExpanderPlugins(CompanyUserTransfer $companyUserTransfer): array;
}
