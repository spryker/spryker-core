<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\Provider;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestAddressTransfer;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Dependency\Facade\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface;

class CompanyBusinessUnitAddressProvider implements CompanyBusinessUnitAddressProviderInterface
{
    /**
     * @var \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Dependency\Facade\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface
     */
    protected $companyUnitAddressFacade;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Dependency\Facade\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade
     */
    public function __construct(CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface $companyUnitAddressFacade)
    {
        $this->companyUnitAddressFacade = $companyUnitAddressFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\RestAddressTransfer $restAddressTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function provideCompanyBusinessUnitAddress(
        RestAddressTransfer $restAddressTransfer,
        QuoteTransfer $quoteTransfer
    ): AddressTransfer {
        $companyUnitAddressResponseTransfer = $this->companyUnitAddressFacade->findCompanyBusinessUnitAddressByUuid(
            (new CompanyUnitAddressTransfer())->setUuid($restAddressTransfer->getIdCompanyBusinessUnitAddress())
        );

        if (
            !$companyUnitAddressResponseTransfer->getIsSuccessful()
            || !$companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer()
        ) {
            return (new AddressTransfer())->fromArray($restAddressTransfer->toArray(), true);
        }

        return (new AddressTransfer())
            ->fromArray($companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer()->toArray(), true)
            ->setUuid(null)
            ->setIsAddressSavingSkipped(true)
            ->setEmail($quoteTransfer->getCustomer()->getEmail())
            ->setFirstName($quoteTransfer->getCustomer()->getFirstName())
            ->setLastName($quoteTransfer->getCustomer()->getLastName())
            ->setSalutation($quoteTransfer->getCustomer()->getSalutation())
            ->setCompany($companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer()->getCompany()->getName());
    }
}
