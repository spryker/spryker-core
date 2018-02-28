<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyRole;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyRoleResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CompanyRole\CompanyRoleFactory getFactory()
 */
class CompanyRoleClient extends AbstractClient implements CompanyRoleClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleResponseTransfer
     */
    public function createCompanyRole(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleResponseTransfer
    {
        return $this->getFactory()
            ->createZedCompanyRoleStub()
            ->createCompanyRole($companyRoleTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleCriteriaFilterTransfer $companyRoleCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    public function getCompanyRoleCollection(
        CompanyRoleCriteriaFilterTransfer $companyRoleCriteriaFilterTransfer
    ): CompanyRoleCollectionTransfer {
        return $this->getFactory()
            ->createZedCompanyRoleStub()
            ->getCompanyRoleCollection($companyRoleCriteriaFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    public function getCompanyRoleById(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleTransfer
    {
        return $this->getFactory()
            ->createZedCompanyRoleStub()
            ->getCompanyRoleById($companyRoleTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return void
     */
    public function updateCompanyRole(CompanyRoleTransfer $companyRoleTransfer): void
    {
        $this->getFactory()
            ->createZedCompanyRoleStub()
            ->updateCompanyRole($companyRoleTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return void
     */
    public function deleteCompanyRole(CompanyRoleTransfer $companyRoleTransfer): void
    {
        $this->getFactory()
            ->createZedCompanyRoleStub()
            ->deleteCompanyRole($companyRoleTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyRoleTransfer $companyRoleTransfer
     *
     * @return \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    public function findCompanyRolePermissions(CompanyRoleTransfer $companyRoleTransfer): PermissionCollectionTransfer
    {
        return $this->getFactory()
            ->createZedCompanyRoleStub()
            ->findCompanyRolePermissions($companyRoleTransfer);
    }
}
