<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalf\Persistence;

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
     * @param int $idCustomer
     *
     * @return bool
     */
    public function isOnBehalfByCustomerId(int $idCustomer): bool
    {
        $query = $this->getFactory()->getCompanyUserQuery();
        $query->filterByFkCustomer($idCustomer);

        return $query->exists();
    }

    /**
     * {@inheritdoc}
     *
     * @api
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
}
