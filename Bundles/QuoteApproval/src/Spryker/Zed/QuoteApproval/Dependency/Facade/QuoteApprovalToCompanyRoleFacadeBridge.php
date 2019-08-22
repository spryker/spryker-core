<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Dependency\Facade;

use Generated\Shared\Transfer\PermissionCollectionTransfer;

class QuoteApprovalToCompanyRoleFacadeBridge implements QuoteApprovalToCompanyRoleFacadeInterface
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
     * @param string $permissionKey
     *
     * @return int[]
     */
    public function getCompanyUserIdsByPermissionKey(string $permissionKey): array
    {
        return $this->companyRoleFacade->getCompanyUserIdsByPermissionKey($permissionKey);
    }

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findPermissionsByIdCompanyUser(int $idCompanyUser): PermissionCollectionTransfer
    {
        return $this->companyRoleFacade->findPermissionsByIdCompanyUser($idCompanyUser);
    }
}
