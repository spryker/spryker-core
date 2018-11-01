<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;

interface CompanyBusinessUnitPreDeletePluginInterface
{
    /**
     * Specification:
     * - Plugin is triggered before company business unit object is deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return void
     */
    public function preDelete(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): void;
}
