<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;

interface CompanyUserPreSavePluginInterface
{
    /**
     * Specification:
     * - Executes plugins before a company user is saved
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function preSave(CompanyUserResponseTransfer $companyUserResponseTransfer): CompanyUserResponseTransfer;
}
