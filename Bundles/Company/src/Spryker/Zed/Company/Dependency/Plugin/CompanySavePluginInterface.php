<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Dependency;

use Generated\Shared\Transfer\CompanyTransfer;

interface CompanySavePluginInterface
{
    /**
     * Specification:
     *  - This plugin executed after add and update operations
     *
     * CompanyTransfer $companyTransfer
     *
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return void
     */
    public function postSave(CompanyTransfer $companyTransfer);
}
