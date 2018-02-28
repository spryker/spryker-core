<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRole\Persistence;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;

interface CompanyRoleRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function getCompanyRoleById(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleTransfer;

    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findPermissionsByIdCompanyUser(int $idCompanyUser): PermissionCollectionTransfer;

    /**
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    public function findCompanyRole(): CompanyRoleCollectionTransfer;

    /**
     * @param int $idCompanyRole
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findCompanyRolePermissions(int $idCompanyRole): PermissionCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer $companyRoleCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    public function getCompanyRoleCollection(
        CompanyRoleCriteriaFilterTransfer $companyRoleCriteriaFilterTransfer
    ): CompanyRoleCollectionTransfer;
}
