<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;

interface CompanyUserStorageExpanderPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer $companyUserStorageTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserStorageTransfer
     */
    public function expand(CompanyUserStorageTransfer $companyUserStorageTransfer): CompanyUserStorageTransfer;
}
