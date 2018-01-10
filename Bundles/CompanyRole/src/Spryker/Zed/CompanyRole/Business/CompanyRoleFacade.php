<?php

namespace Spryker\Zed\CompanyRole\Business;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

class CompanyRoleFacade
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param CompanyRoleTransfer $companyRoleTransfer
     *
     * @return CompanyRoleTransfer
     */
    public function create(CompanyRoleTransfer $companyRoleTransfer): CompanyRoleTransfer
    {
        return $companyRoleTransfer->setIdCompanyRole(1);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param CompanyRoleTransfer $companyRoleTransfer
     *
     * @return void
     */
    public function update(CompanyRoleTransfer $companyRoleTransfer)
    {

    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param CompanyRoleTransfer $companyRoleTransfer
     *
     * @return void
     */
    public function delete(CompanyRoleTransfer $companyRoleTransfer)
    {

    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function saveCompanyUser(CompanyUserTransfer $companyUserTransfer)
    {

    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param CompanyUserTransfer $companyUserTransfer
     *
     * @return CompanyUserTransfer
     */
    public function hydrateCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        return $companyUserTransfer->setCompanyRoleCollection(new CompanyRoleCollectionTransfer());
    }
}