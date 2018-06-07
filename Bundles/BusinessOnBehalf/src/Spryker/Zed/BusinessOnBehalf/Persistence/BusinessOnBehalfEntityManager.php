<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalf\Persistence;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfPersistenceFactory getFactory()
 */
class BusinessOnBehalfEntityManager extends AbstractEntityManager implements BusinessOnBehalfEntityManagerInterface
{
    /**
     * @uses \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function setDefaultCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        $companyUserTransfer
            ->requireCustomer()
            ->getCustomer()
                ->requireIdCustomer();

        $this->cleanupExistingIsDefaultFlag($companyUserTransfer->getCustomer());

        $defaultCompanyUser = $this->getFactory()
            ->getCompanyUserQuery()
            ->filterByIdCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->filterByFkCustomer($companyUserTransfer->getCustomer()->getIdCustomer())
            ->findOne();

        if ($defaultCompanyUser) {
            $defaultCompanyUser->setIsDefault(true)->save();
        }

        return $companyUserTransfer->fromArray($defaultCompanyUser->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function unsetDefaultCompanyUserByCustomer(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $this->cleanupExistingIsDefaultFlag($customerTransfer);

        return $customerTransfer;
    }

    /**
     * @uses \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function cleanupExistingIsDefaultFlag(CustomerTransfer $customerTransfer): void
    {
        $customerTransfer->requireIdCustomer();

        $this->getFactory()
            ->getCompanyUserQuery()
            ->filterByFkCustomer($customerTransfer->getIdCustomer())
            ->filterByIsDefault(true)
            ->update(['IsDefault' => false]);
    }
}
