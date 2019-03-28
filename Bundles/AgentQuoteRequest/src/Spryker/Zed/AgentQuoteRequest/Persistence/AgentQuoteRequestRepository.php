<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentQuoteRequest\Persistence;

use Generated\Shared\Transfer\CompanyUserQueryTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\AgentQuoteRequest\Persistence\AgentQuoteRequestPersistenceFactory getFactory()
 */
class AgentQuoteRequestRepository extends AbstractRepository implements AgentQuoteRequestRepositoryInterface
{
    /**
     * @modules Company
     * @modules CompanyBusinessUnit
     * @modules Customer
     *
     * @param \Generated\Shared\Transfer\CompanyUserQueryTransfer $companyUserQueryTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    public function getCompanyUsersByQuery(CompanyUserQueryTransfer $companyUserQueryTransfer): array
    {
        $queryPattern = $companyUserQueryTransfer->getQuery() . '%';

        $companyUsersQuery = $this->getFactory()
            ->getCompanyUserPropelQuery()
            ->joinWithCompanyBusinessUnit()
            ->joinWithCompany()
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
                SpyCompanyTableMap::COL_NAME,
                SpyCompanyBusinessUnitTableMap::COL_NAME,
                SpyCustomerTableMap::COL_FIRST_NAME,
                SpyCustomerTableMap::COL_LAST_NAME,
                SpyCustomerTableMap::COL_EMAIL,
            ]);

        if ($companyUserQueryTransfer->getLimit()) {
            $companyUsersQuery->limit($companyUserQueryTransfer->getLimit());
        }

        return $this->getFactory()
            ->createCompanyUserMapper()
            ->mapCompanyUserCollectionToTransfers($companyUsersQuery->find());
    }
}
