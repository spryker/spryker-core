<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Dependency\Facade;

use Generated\Shared\Transfer\CompanyRoleTransfer;

class CompanyRoleGuiToCompanyRoleFacadeBridge implements CompanyRoleGuiToCompanyRoleFacadeInterface
{
    /**
     * @var \Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface
     */
    protected $companyRoleFacade;

    /**
     * @param \Spryker\Zed\CompanyRole\Business\CompanyRoleFacadeInterface $companyRoleFacade
     */
    public function __construct($companyRoleFacade)
    {
        $this->companyRoleFacade = $companyRoleFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function getCompanyRoleById(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleTransfer
    {
        return $this->companyRoleFacade->getCompanyRoleById($companyRoleTransfer);
    }
}
