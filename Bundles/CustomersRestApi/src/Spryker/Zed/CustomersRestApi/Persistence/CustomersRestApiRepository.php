<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi\Persistence;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\CustomersRestApi\Persistence\CustomersRestApiPersistenceFactory getFactory()
 */
class CustomersRestApiRepository extends AbstractRepository implements CustomersRestApiRepositoryInterface
{
    /**
     * @param string $addressUuid
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    public function findCustomerAddressByUuid(string $addressUuid, int $idCustomer): ?AddressTransfer
    {
        $addressesPropelQuery = $this->getFactory()
            ->getAddressesPropelQuery()
            ->joinWithCountry(Criteria::INNER_JOIN)
            ->filterByFkCustomer($idCustomer)
            ->filterByUuid($addressUuid);

        $customerAddressEntityTransfer = $this->buildQueryFromCriteria($addressesPropelQuery)->findOne();

        if ($customerAddressEntityTransfer === null) {
            return null;
        }

        return $this
            ->getFactory()
            ->createCustomerAddressPersistenceMapper()
            ->mapCustomerAddressEntityTransferToAddressTransfer($customerAddressEntityTransfer);
    }
}
