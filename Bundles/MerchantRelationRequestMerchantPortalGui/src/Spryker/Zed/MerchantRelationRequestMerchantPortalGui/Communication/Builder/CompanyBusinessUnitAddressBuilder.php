<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Builder;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

class CompanyBusinessUnitAddressBuilder implements CompanyBusinessUnitAddressBuilderInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CompanyBusinessUnitTransfer> $companyBusinessUnitTransfers
     *
     * @return array<int, list<string>>
     */
    public function buildCompanyBusinessUnitAddressesGroupedByIdCompanyBusinessUnit(
        ArrayObject $companyBusinessUnitTransfers
    ): array {
        $companyBusinessUnitAddressesIndexedByIdCompanyBusinessUnit = [];

        foreach ($companyBusinessUnitTransfers as $companyBusinessUnitTransfer) {
            if (!$companyBusinessUnitTransfer->getAddressCollection()) {
                continue;
            }

            $idCompanyBusinessUnit = $companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail();
            $companyBusinessUnitAddressesIndexedByIdCompanyBusinessUnit[$idCompanyBusinessUnit] = $this->getCompanyBusinessUnitAddresses(
                $companyBusinessUnitTransfer->getAddressCollection(),
            );
        }

        return $companyBusinessUnitAddressesIndexedByIdCompanyBusinessUnit;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
     *
     * @return list<string>
     */
    protected function getCompanyBusinessUnitAddresses(
        CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
    ): array {
        $companyBusinessUnitAddresses = [];

        foreach ($companyUnitAddressCollectionTransfer->getCompanyUnitAddresses() as $companyUnitAddressTransfer) {
            $companyBusinessUnitAddresses[] = $this->buildCompanyBusinessUnitAddress($companyUnitAddressTransfer);
        }

        return $companyBusinessUnitAddresses;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return string
     */
    protected function buildCompanyBusinessUnitAddress(CompanyUnitAddressTransfer $companyUnitAddressTransfer): string
    {
        $companyBusinessUnitAddress = sprintf('%s %s', $companyUnitAddressTransfer->getAddress1(), $companyUnitAddressTransfer->getAddress2());

        if ($companyUnitAddressTransfer->getAddress3()) {
            $companyBusinessUnitAddress .= sprintf(', %s', $companyUnitAddressTransfer->getAddress3());
        }

        if ($companyUnitAddressTransfer->getZipCode()) {
            $companyBusinessUnitAddress .= sprintf(', %s', $companyUnitAddressTransfer->getZipCodeOrFail());
        }

        if ($companyUnitAddressTransfer->getCity()) {
            $companyBusinessUnitAddress .= sprintf(' %s', $companyUnitAddressTransfer->getCityOrFail());
        }

        $countryTransfer = $companyUnitAddressTransfer->getCountry();

        if ($countryTransfer && $countryTransfer->getName()) {
            $companyBusinessUnitAddress .= sprintf(' - %s', $countryTransfer->getName());
        }

        return $companyBusinessUnitAddress;
    }
}
