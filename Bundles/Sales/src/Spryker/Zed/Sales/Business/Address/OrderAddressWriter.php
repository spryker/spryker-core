<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Address;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Sales\Business\Mapper\SalesMapperInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerInterface;
use Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class OrderAddressWriter implements OrderAddressWriterInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface
     */
    protected $countryFacade;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\Sales\Business\Mapper\SalesMapperInterface
     */
    protected $salesMapper;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $repository
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface $countryFacade
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCustomerInterface $customerFacade
     * @param \Spryker\Zed\Sales\Business\Mapper\SalesMapperInterface $salesMapper
     */
    public function __construct(
        SalesEntityManagerInterface $entityManager,
        SalesRepositoryInterface $repository,
        SalesToCountryInterface $countryFacade,
        SalesToCustomerInterface $customerFacade,
        SalesMapperInterface $salesMapper
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->countryFacade = $countryFacade;
        $this->customerFacade = $customerFacade;
        $this->salesMapper = $salesMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function create(AddressTransfer $addressTransfer): AddressTransfer
    {
        $addressTransfer->setFkCountry(
            $this->countryFacade->getIdCountryByIso2Code($addressTransfer->getIso2Code())
        );

        if ($addressTransfer->getIdSalesOrderAddress()) {
            $this->update($addressTransfer, $addressTransfer->getIdSalesOrderAddress());

            return $addressTransfer;
        }

        return $this->entityManager->createSalesOrderAddress($addressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param int $idAddress
     *
     * @return bool
     */
    public function update(AddressTransfer $addressTransfer, int $idAddress): bool
    {
        $foundAddressTransfer = $this->resolveAddressSource($addressTransfer);
        if ($foundAddressTransfer === null) {
            return false;
        }

        $this->entityManager->updateSalesOrderAddress($foundAddressTransfer);

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    protected function resolveAddressSource(AddressTransfer $addressTransfer): ?AddressTransfer
    {
        if ($addressTransfer->getIdCustomerAddress() !== null) {
            $foundAddressTransfer = $this->customerFacade->findCustomerAddressById($addressTransfer->getIdCustomerAddress());
            if ($foundAddressTransfer === null) {
                return null;
            }

            return $foundAddressTransfer->setIdSalesOrderAddress($addressTransfer->getIdSalesOrderAddress());
        }

        $foundAddressTransfer = $this->repository->findOrderAddressByIdOrderAddress($addressTransfer->getIdSalesOrderAddress());
        if ($foundAddressTransfer === null) {
            return null;
        }

        return $this->salesMapper->mapAddressTransferToAddressTransfer($foundAddressTransfer, $addressTransfer);
    }
}
