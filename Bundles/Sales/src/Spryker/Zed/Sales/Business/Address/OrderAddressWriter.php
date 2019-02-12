<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Address;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
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
     * @param \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $repository
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface $countryFacade
     */
    public function __construct(
        SalesEntityManagerInterface $entityManager,
        SalesRepositoryInterface $repository,
        SalesToCountryInterface $countryFacade
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->countryFacade = $countryFacade;
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

        if ($addressTransfer->getIdSalesOrderAddress() !== null) {
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
        $foundAddressTransfer = $this->repository->findOrderAddressByIdOrderAddress($idAddress);

        if ($foundAddressTransfer === null) {
            return false;
        }

        $foundAddressTransfer = $this->hydrateAddressTransferFromModifiedAddressTransfer($foundAddressTransfer, $addressTransfer);
        $this->entityManager->updateSalesOrderAddress($foundAddressTransfer);

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\AddressTransfer $modifiedAddressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function hydrateAddressTransferFromModifiedAddressTransfer(
        AddressTransfer $addressTransfer,
        AddressTransfer $modifiedAddressTransfer
    ): AddressTransfer {
        $addressTransfer->fromArray($modifiedAddressTransfer->modifiedToArray());

        return $addressTransfer;
    }
}
