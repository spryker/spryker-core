<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyUserSavePreCheckPluginInterface
{
    /**
     * Specification:
     * - Executes checks before a company user saving process is started.
     * - Cancels saving if any check failed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function check(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer;
}
