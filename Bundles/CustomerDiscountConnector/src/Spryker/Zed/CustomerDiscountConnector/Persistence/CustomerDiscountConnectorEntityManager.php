<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDiscountConnector\Persistence;

use Orm\Zed\CustomerDiscountConnector\Persistence\SpyCustomerDiscount;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Propel\Persistence\BatchProcessor\ActiveRecordBatchProcessorTrait;

/**
 * @method \Spryker\Zed\CustomerDiscountConnector\Persistence\CustomerDiscountConnectorPersistenceFactory getFactory()
 */
class CustomerDiscountConnectorEntityManager extends AbstractEntityManager implements CustomerDiscountConnectorEntityManagerInterface
{
    use ActiveRecordBatchProcessorTrait;

    /**
     * @param int $idCustomer
     * @param array<int> $discountIds
     *
     * @return void
     */
    public function createCustomerDiscounts(int $idCustomer, array $discountIds): void
    {
        foreach ($discountIds as $idDiscount) {
            $customerDiscountEntity = new SpyCustomerDiscount();
            $customerDiscountEntity->setFkCustomer($idCustomer);
            $customerDiscountEntity->setFkDiscount($idDiscount);
            $this->persist($customerDiscountEntity);
        }

        $this->commit();
    }
}
