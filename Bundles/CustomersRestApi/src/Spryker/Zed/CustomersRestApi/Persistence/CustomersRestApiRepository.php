<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CustomersRestApi\Persistence\CustomersRestApiPersistenceFactory getFactory()
 */
class CustomersRestApiRepository extends AbstractRepository implements CustomersRestApiRepositoryInterface
{
    /**
     * @param string $addressUuid
     * @param int $idCustomer
     *
     * @return int|null
     */
    public function findCustomerIdCustomerAddressByUuid(string $addressUuid, int $idCustomer): ?int
    {
        $addressesPropelQuery = $this->getFactory()->getAddressesPropelQuery();

        $customerAddressEntity = $addressesPropelQuery
            ->filterByFkCustomer($idCustomer)
            ->findOneByUuid($addressUuid);

        if ($customerAddressEntity === null) {
            return null;
        }

        return $customerAddressEntity->getIdCustomerAddress();
    }
}
