<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDiscountConnector\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CustomerDiscountConnector\Persistence\CustomerDiscountConnectorPersistenceFactory getFactory()
 */
class CustomerDiscountConnectorRepository extends AbstractRepository implements CustomerDiscountConnectorRepositoryInterface
{
    /**
     * @param int $idCustomer
     * @param int $idDiscount
     *
     * @return int
     */
    public function countCustomerDiscountUsages(int $idCustomer, int $idDiscount): int
    {
        return $this->getFactory()
            ->createCustomerDiscountQuery()
            ->filterByFkCustomer($idCustomer)
            ->filterByFkDiscount($idDiscount)
            ->count();
    }
}
