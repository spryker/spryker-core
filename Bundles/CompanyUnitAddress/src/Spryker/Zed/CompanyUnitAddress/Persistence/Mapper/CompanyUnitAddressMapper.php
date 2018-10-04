<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer;

class CompanyUnitAddressMapper implements CompanyUnitAddressMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer $unitAddressEntityTransfer
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $unitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function mapEntityTransferToCompanyUnitAddressTransfer(
        SpyCompanyUnitAddressEntityTransfer $unitAddressEntityTransfer,
        CompanyUnitAddressTransfer $unitAddressTransfer
    ): CompanyUnitAddressTransfer {

        $companyUnitAddressTransfer = (new CompanyUnitAddressTransfer())->fromArray(
            $unitAddressEntityTransfer->toArray(),
            true
        );

        $companyUnitAddressTransfer->setIso2Code($unitAddressEntityTransfer->getCountry()->getIso2Code());

        $companyBusinessUnitTransfers = $this->mapCompanyBusinessUnitCollection($unitAddressEntityTransfer);
        $companyUnitAddressTransfer->setCompanyBusinessUnits($companyBusinessUnitTransfers);

        return $companyUnitAddressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer $spyCompanyUnitAddressEntityTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    protected function mapCompanyBusinessUnitCollection(SpyCompanyUnitAddressEntityTransfer $spyCompanyUnitAddressEntityTransfer): CompanyBusinessUnitCollectionTransfer
    {
        $companyBusinessUnitCollectionTransfer = new CompanyBusinessUnitCollectionTransfer();
        foreach ($spyCompanyUnitAddressEntityTransfer->getSpyCompanyUnitAddressToCompanyBusinessUnits() as $spyCompanyUnitAddressToCompanyBusinessUnits) {
            $spyCompanyBusinessUnitEntityTransfer = $spyCompanyUnitAddressToCompanyBusinessUnits->getCompanyBusinessUnit();
            $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())
                ->fromArray($spyCompanyBusinessUnitEntityTransfer->toArray(), true);
            $companyBusinessUnitCollectionTransfer->addCompanyBusinessUnit($companyBusinessUnitTransfer);
        }

        return $companyBusinessUnitCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer $unitAddressEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer
     */
    public function mapCompanyUnitAddressTransferToEntityTransfer(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        SpyCompanyUnitAddressEntityTransfer $unitAddressEntityTransfer
    ): SpyCompanyUnitAddressEntityTransfer {
        $companyUnitAddressEntityTransfer = (new SpyCompanyUnitAddressEntityTransfer())->fromArray(
            $companyUnitAddressTransfer->modifiedToArray(),
            true
        );

        return $companyUnitAddressEntityTransfer;
    }
}
