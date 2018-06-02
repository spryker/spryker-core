<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalf\Persistence;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfPersistenceFactory getFactory()
 */
class BusinessOnBehalfEntityManager extends AbstractRepository implements BusinessOnBehalfEntityManagerInterface
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
        $this->deselectExistingIsDefaultFlag($companyUserTransfer->requireCustomer()->getCustomer());

        $query = $this->getFactory()->getCompanyUserQuery();
        $defaultCompanyUser = $query->filterByIdCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->filterByFkCustomer($companyUserTransfer->getCustomer()->getIdCustomer())
            ->findOne();
        if ($defaultCompanyUser) {
            $defaultCompanyUser->setIsDefault(true)->save();
        }

        return $companyUserTransfer;
    }

    /**
     * @uses CompanyUser
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function deselectExistingIsDefaultFlag(CustomerTransfer $customerTransfer): void
    {
        $query = $this->getFactory()->getCompanyUserQuery();
        $defaultCompanyUsers = $query->filterByFkCompany($customerTransfer->getIdCustomer())
            ->filterByIsDefault(true)
            ->find();

        foreach ($defaultCompanyUsers as $defaultCompanyUser) {
            $defaultCompanyUser->setIsDefault(null)->save();
        }
    }
}
