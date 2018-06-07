<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalf\Persistence;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\BusinessOnBehalf\Persistence\BusinessOnBehalfPersistenceFactory getFactory()
 */
class BusinessOnBehalfRepository extends AbstractRepository implements BusinessOnBehalfRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
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

        return count($query->select([SpyCompanyUserTableMap::COL_ID_COMPANY_USER])->find()) > 1;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @uses \Orm\Zed\Company\Persistence\SpyCompanyQuery
     * @uses \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     * @uses \Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap
     *
     * @param int $idCustomer
     *
     * @return int[]
     */
    public function findActiveCompanyUserIdsByCustomerId(int $idCustomer): array
    {
        $query = $this->getFactory()->getCompanyUserQuery();
        $query->filterByFkCustomer($idCustomer)
            ->joinCompany()
            ->useCompanyQuery()
                ->filterByIsActive(true)
            ->endUse();
        $query->select(SpyCompanyUserTableMap::COL_ID_COMPANY_USER);

        return $query->find()->toArray();
    }

    /**
     * @uses \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findDefaultCompanyUserByCustomerId(int $idCustomer): ?CompanyUserTransfer
    {
        $query = $this->getFactory()->getCompanyUserQuery();
        $spyCompanyUser = $query->filterByFkCustomer($idCustomer)
            ->filterByIsDefault(true)
            ->findOne();
        if (!$spyCompanyUser) {
            return null;
        }

        return (new CompanyUserTransfer())->fromArray($spyCompanyUser->toArray());
    }
}
