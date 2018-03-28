<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CompanyResponseTransfer;

interface CompanyPostSavePluginInterface
{
    /**
     * Specification:
     * - Plugin is triggered after company object is saved.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function postSave(CompanyResponseTransfer $companyResponseTransfer): CompanyResponseTransfer;
}
