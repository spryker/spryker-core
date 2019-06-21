<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Address;

use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Sales\Business\Expander\SalesAddressExpanderInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface;

class OrderAddressWriter implements OrderAddressWriterInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface
     */
    protected $countryFacade;

    /**
     * @var \Spryker\Zed\Sales\Business\Expander\SalesAddressExpanderInterface
     */
    protected $salesAddressExpander;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface $countryFacade
     * @param \Spryker\Zed\Sales\Business\Expander\SalesAddressExpanderInterface $salesAddressExpander
     */
    public function __construct(
        SalesEntityManagerInterface $entityManager,
        SalesToCountryInterface $countryFacade,
        SalesAddressExpanderInterface $salesAddressExpander
    ) {
        $this->entityManager = $entityManager;
        $this->countryFacade = $countryFacade;
        $this->salesAddressExpander = $salesAddressExpander;
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
        $foundAddressTransfer = $this->salesAddressExpander->expandWithCustomerOrSalesAddress($addressTransfer);
        if ($foundAddressTransfer === null) {
            return false;
        }

        $this->entityManager->updateSalesOrderAddress($foundAddressTransfer);

        return true;
    }
}
