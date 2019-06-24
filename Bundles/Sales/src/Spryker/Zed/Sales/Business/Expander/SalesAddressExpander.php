<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Expander;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerInterface;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class SalesAddressExpander implements SalesAddressExpanderInterface
{
    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerInterface $customerFacade
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $repository
     */
    public function __construct(SalesToCustomerInterface $customerFacade, SalesRepositoryInterface $repository)
    {
        $this->customerFacade = $customerFacade;
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function expandWithCustomerOrSalesAddress(AddressTransfer $addressTransfer): AddressTransfer
    {
        if ($addressTransfer->getIdCustomerAddress() === null) {
            return $this->expandWithSalesAddress($addressTransfer);
        }

        return $this->expandWithCustomerAddress($addressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function expandWithCustomerAddress(AddressTransfer $addressTransfer): AddressTransfer
    {
        $idCustomerAddress = $addressTransfer->getIdCustomerAddress();
        if ($idCustomerAddress === null) {
            return $addressTransfer;
        }

        $foundAddressTransfer = $this->customerFacade->findCustomerAddressById($idCustomerAddress);
        if ($foundAddressTransfer === null) {
            return $addressTransfer;
        }

        return $foundAddressTransfer->setIdSalesOrderAddress($addressTransfer->getIdSalesOrderAddress());
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function expandWithSalesAddress(AddressTransfer $addressTransfer): AddressTransfer
    {
        $idSalesOrderAddress = $addressTransfer->getIdSalesOrderAddress();
        if ($idSalesOrderAddress === null) {
            return $addressTransfer;
        }

        $foundAddressTransfer = $this->repository->findOrderAddressByIdOrderAddress($addressTransfer->getIdSalesOrderAddress());
        if ($foundAddressTransfer === null) {
            return $addressTransfer;
        }

        return $foundAddressTransfer->fromArray($addressTransfer->modifiedToArray(), true);
    }
}
