<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;

interface CompanyBusinessUnitExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands the provided company business unit transfer data and returns the modified object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function expand(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitTransfer;
}
