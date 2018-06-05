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
     * @uses CompanyUser
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function setDefaultCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        $this->cleanupExistingIsDefaultFlag($companyUserTransfer->requireCustomer()->getCustomer());

        $query = $this->getFactory()->getCompanyUserQuery();
        $defaultCompanyUser = $query->filterByIdCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->filterByFkCustomer($companyUserTransfer->getCustomer()->getIdCustomer())
            ->findOne();

        if ($defaultCompanyUser && $companyUserTransfer->getIsDefault()) {
            $defaultCompanyUser->setIsDefault(true)->save();
        }

        return $companyUserTransfer->fromArray($defaultCompanyUser->toArray(), true);
    }

    /**
     * @uses CompanyUser
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function cleanupExistingIsDefaultFlag(CustomerTransfer $customerTransfer): void
    {
        $this->getFactory()
            ->getCompanyUserQuery()
            ->filterByFkCustomer($customerTransfer->getIdCustomer())
            ->filterByIsDefault(true)
            ->update(['IsDefault' => null]);
    }
}
