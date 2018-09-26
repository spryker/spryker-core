<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyRoleTransfer;

interface CompanyRoleCreateOrUpdateFormDataProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer|null $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function getData(?CompanyRoleTransfer $companyRoleTransfer = null): CompanyRoleTransfer;

    /**
     * @return array
     */
    public function getOptions(): array;
}
