<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Business;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CompanyUser\Business\CompanyUserBusinessFactory getFactory()
 * @method \Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyUser\Persistence\CompanyUserEntityManagerInterface getEntityManager()
 */
class CompanyUserFacade extends AbstractFacade implements CompanyUserFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function create(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getFactory()
            ->createCompanyUser()
            ->create($companyUserTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function update(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getFactory()
            ->createCompanyUser()
            ->save($companyUserTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function delete(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getFactory()
            ->createCompanyUser()
            ->delete($companyUserTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findCompanyUserByCustomerId(CustomerTransfer $customerTransfer): ?CompanyUserTransfer
    {
        return $this->getFactory()
            ->createCompanyUser()
            ->findCompanyUserByCustomerId($customerTransfer->getIdCustomer());
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findActiveCompanyUserByCustomerId(CustomerTransfer $customerTransfer): ?CompanyUserTransfer
    {
        return $this->getFactory()
            ->createCompanyUser()
            ->findActiveCompanyUserByCustomerId($customerTransfer->getIdCustomer());
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollection(
        CompanyUserCriteriaFilterTransfer $companyUserCriteriaFilterTransfer
    ): CompanyUserCollectionTransfer {
        return $this->getFactory()
            ->createCompanyUser()
            ->getCompanyUserCollection($companyUserCriteriaFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCompanyUserById(int $idCompanyUser): CompanyUserTransfer
    {
        return $this->getRepository()->getCompanyUserById($idCompanyUser);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findInitialCompanyUserByCompanyId(int $idCompany): ?CompanyUserTransfer
    {
        return $this->getRepository()->findInitialCompanyUserByCompanyId($idCompany);
    }
}
