<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Dependency\Facade;

use Generated\Shared\Transfer\CompanyTransfer;

interface CompanyGuiToCompanyFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return void
     */
    public function update(CompanyTransfer $companyTransfer): void;
}
