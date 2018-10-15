<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Helper;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;

class CompanyRoleGuiHelper implements CompanyRoleGuiHelperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyRoleCollectionTransfer $companyRoleCollectionTransfer
     *
     * @return string
     */
    public function getCompanyRoleNames(CompanyRoleCollectionTransfer $companyRoleCollectionTransfer): string
    {
        $companyUserRoleNames = '';

        foreach ($companyRoleCollectionTransfer->getRoles() as $companyRoleTransfer) {
            $companyUserRoleNames .= '<p>' . $companyRoleTransfer->getName() . '</p>';
        }

        return $companyUserRoleNames;
    }
}
