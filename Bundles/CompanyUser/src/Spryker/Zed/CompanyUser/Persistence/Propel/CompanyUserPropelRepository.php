<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Persistence\Propel;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserPersistenceFactory;
use Spryker\Zed\CompanyUser\Persistence\CompanyUserRepositoryInterface;

class CompanyUserPropelRepository implements CompanyUserRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function findCompanyUserByCustomerId(CustomerTransfer $customerTransfer): ?CompanyUserTransfer
    {
        $customerTransfer->requireIdCustomer();

        $companyUserEntity = $this->queryCompanyUser()
            ->filterByFkCustomer($customerTransfer->getIdCustomer())
            ->findOne();

        if ($companyUserEntity === null) {
            return null;
        }

        return $this->getFactory()->createCompanyUserMapper()->mapCompanyUserEntityToTransfer($companyUserEntity);
    }

    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    protected function queryCompanyUser(): SpyCompanyUserQuery
    {
        return $this->getFactory()->createCompanyUserQuery();
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Persistence\CompanyUserPersistenceFactory
     */
    protected function getFactory(): CompanyUserPersistenceFactory
    {
        return new CompanyUserPersistenceFactory();
    }
}
