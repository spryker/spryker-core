<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomersRestApi\Business;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CustomersRestApi\Business\CustomersRestApiBusinessFactory getFactory()
 */
class CustomersRestApiFacade extends AbstractFacade implements CustomersRestApiFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function updateCustomerAddressUuid(): void
    {
        $this->getFactory()->createCustomersAddressesUuidUpdater()->updateAddressesUuid();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $addressId
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    public function findCustomerAddressById(string $addressId, int $idCustomer): ?AddressTransfer
    {
        return $this->getFactory()->createCustomerAddressReader()->findCustomerAddressById($addressId, $idCustomer);
    }
}
