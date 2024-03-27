<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Builder;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

class CompanyBusinessUnitAddressBuilder implements CompanyBusinessUnitAddressBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return string
     */
    public function buildCompanyBusinessUnitAddress(CompanyUnitAddressTransfer $companyUnitAddressTransfer): string
    {
        $companyBusinessUnitAddress = sprintf(
            '%s %s',
            $companyUnitAddressTransfer->getAddress1OrFail(),
            $companyUnitAddressTransfer->getAddress2OrFail(),
        );

        if ($companyUnitAddressTransfer->getAddress3()) {
            $companyBusinessUnitAddress .= sprintf(', %s', $companyUnitAddressTransfer->getAddress3OrFail());
        }

        if ($companyUnitAddressTransfer->getZipCode()) {
            $companyBusinessUnitAddress .= sprintf(', %s', $companyUnitAddressTransfer->getZipCodeOrFail());
        }

        if ($companyUnitAddressTransfer->getCity()) {
            $companyBusinessUnitAddress .= sprintf(' %s', $companyUnitAddressTransfer->getCityOrFail());
        }

        $countryTransfer = $companyUnitAddressTransfer->getCountry();

        if ($countryTransfer && $countryTransfer->getName()) {
            $companyBusinessUnitAddress .= sprintf(' - %s', $countryTransfer->getNameOrFail());
        }

        return $companyBusinessUnitAddress;
    }
}
