<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentQuoteRequest\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;
use Propel\Runtime\Collection\Collection;

class CompanyUserMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection $companyUsersEntities
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    public function mapCompanyUserEntityCollectionToTransfers(Collection $companyUsersEntities): array
    {
        $companyUserTransfers = [];

        foreach ($companyUsersEntities as $companyUser) {
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
