<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentQuoteRequest\Persistence;

use Generated\Shared\Transfer\CompanyUserQueryTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\AgentQuoteRequest\Persistence\AgentQuoteRequestPersistenceFactory getFactory()
 */
class AgentQuoteRequestRepository extends AbstractRepository implements AgentQuoteRequestRepositoryInterface
{
    /**
     * @modules Customer
     *
     * @param \Generated\Shared\Transfer\CompanyUserQueryTransfer $companyUserQueryTransfer
     *
     * @return array
     */
    public function findCompanyUsersByQuery(CompanyUserQueryTransfer $companyUserQueryTransfer): array
    {
        $queryPattern = $companyUserQueryTransfer->getQuery() . '%';

        $companyUsersQuery = $this->getFactory()
            ->getCompanyUserPropelQuery()
            ->joinWithCustomer()
            ->useCustomerQuery()
                ->filterByEmail_Like($queryPattern)
                ->_or()
                ->filterByLastName_Like($queryPattern)
                ->_or()
                ->filterByFirstName_Like($queryPattern)
                ->setIgnoreCase(true)
            ->endUse()
            ->select([
                SpyCompanyUserTableMap::COL_ID_COMPANY_USER,
                SpyCustomerTableMap::COL_FIRST_NAME,
                SpyCustomerTableMap::COL_LAST_NAME,
                SpyCustomerTableMap::COL_EMAIL,
            ]);

        if ($companyUserQueryTransfer->getLimit()) {
            $companyUsersQuery->limit($companyUserQueryTransfer->getLimit());
        }

        $companyUsers = $companyUsersQuery->find();

        $companyUserTransfers = [];

        foreach ($companyUsers as $companyUser) {
            $customerTransfer = (new CustomerTransfer())
                ->setFirstName($companyUser[SpyCustomerTableMap::COL_FIRST_NAME])
                ->setLastName($companyUser[SpyCustomerTableMap::COL_LAST_NAME])
                ->setEmail($companyUser[SpyCustomerTableMap::COL_EMAIL]);

            $companyUserTransfer = (new CompanyUserTransfer())
                ->setIdCompanyUser($companyUser[SpyCompanyUserTableMap::COL_ID_COMPANY_USER])
                ->setCustomer($customerTransfer);

            $companyUserTransfers[] = $companyUserTransfer;
        }

        return $companyUserTransfers;
    }
}
