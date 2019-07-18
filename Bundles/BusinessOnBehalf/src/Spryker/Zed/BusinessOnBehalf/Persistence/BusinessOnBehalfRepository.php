<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalf\Persistence;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfPersistenceFactory getFactory()
 */
class BusinessOnBehalfRepository extends AbstractRepository implements BusinessOnBehalfRepositoryInterface
{
    /**
     * @uses \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     * @uses \Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap
     *
     * @param int $idCustomer
     *
     * @return bool
     */
    public function isOnBehalfByCustomerId(int $idCustomer): bool
    {
        $query = $this->getFactory()->getCompanyUserQuery();
        $query->filterByFkCustomer($idCustomer);

        return $query->count() > 1;
    }

    /**
     * @module Company
     * @module CompanyUser
     *
     * @uses \Orm\Zed\Company\Persistence\SpyCompanyQuery
     * @uses \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     * @uses \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery::filterByIsActive()
     * @uses \Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap
     * @uses \Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyTableMap
     *
     * @param int $idCustomer
     *
     * @return int[]
     */
    public function findActiveCompanyUserIdsByCustomerId(int $idCustomer): array
    {
        $companyUserQuery = $this->getFactory()->getCompanyUserQuery();
        // For BC reasons
        if (method_exists($companyUserQuery, 'filterByIsActive')) {
            $companyUserQuery->filterByIsActive(true);
        }

        $companyUserQuery->filterByFkCustomer($idCustomer)
            ->joinCompany()
            ->useCompanyQuery()
                ->filterByIsActive(true)
                ->filterByStatus(SpyCompanyTableMap::COL_STATUS_APPROVED)
            ->endUse()
            ->orderByIdCompanyUser()
            ->select(SpyCompanyUserTableMap::COL_ID_COMPANY_USER);

        return $companyUserQuery->find()->toArray();
    }

    /**
     * @uses \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     * @uses \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery::filterByIsActive()
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findDefaultCompanyUserByCustomerId(int $idCustomer): ?CompanyUserTransfer
    {
        $companyUserQuery = $this->getFactory()->getCompanyUserQuery();
        // For BC reasons
        if (method_exists($companyUserQuery, 'filterByIsActive')) {
            $companyUserQuery->filterByIsActive(true);
        }

        $spyCompanyUser = $companyUserQuery
            ->filterByFkCustomer($idCustomer)
            ->filterByIsDefault(true)
            ->findOne();

        if (!$spyCompanyUser) {
            return null;
        }

        return (new CompanyUserTransfer())->fromArray($spyCompanyUser->toArray());
    }
}
