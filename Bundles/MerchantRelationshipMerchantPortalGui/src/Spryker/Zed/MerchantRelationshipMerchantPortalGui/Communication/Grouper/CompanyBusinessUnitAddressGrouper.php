<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Grouper;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Builder\CompanyBusinessUnitAddressBuilderInterface;

class CompanyBusinessUnitAddressGrouper implements CompanyBusinessUnitAddressGrouperInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Builder\CompanyBusinessUnitAddressBuilderInterface
     */
    protected CompanyBusinessUnitAddressBuilderInterface $companyBusinessUnitAddressBuilder;

    /**
     * @param \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Builder\CompanyBusinessUnitAddressBuilderInterface $companyBusinessUnitAddressBuilder
     */
    public function __construct(CompanyBusinessUnitAddressBuilderInterface $companyBusinessUnitAddressBuilder)
    {
        $this->companyBusinessUnitAddressBuilder = $companyBusinessUnitAddressBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
     *
     * @return array<int, list<string>>
     */
    public function getCompanyBusinessUnitAddressesGroupedByIdCompanyBusinessUnit(
        CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
    ): array {
        $companyBusinessUnitAddressesIndexedByIdCompanyBusinessUnit = [];

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
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
            $companyBusinessUnitAddresses[] = $this->companyBusinessUnitAddressBuilder->buildCompanyBusinessUnitAddress(
                $companyUnitAddressTransfer,
            );
        }

        return $companyBusinessUnitAddresses;
    }
}
