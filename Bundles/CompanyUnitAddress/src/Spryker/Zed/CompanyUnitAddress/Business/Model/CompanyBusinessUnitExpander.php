<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Business\Model;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface;

class CompanyBusinessUnitExpander implements CompanyBusinessUnitExpanderInterface
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface $repository
     */
    public function __construct(CompanyUnitAddressRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function expandCompanyBusinessUnitWithCompanyUnitAddressCollection(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitTransfer {
        $criteriaFilterTransfer = new CompanyUnitAddressCriteriaFilterTransfer();
        $criteriaFilterTransfer->setIdCompanyBusinessUnit(
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit()
        );

        $companyUnitAddressCollectionTransfer = $this->repository->getCompanyUnitAddressCollection($criteriaFilterTransfer);

        $this->markCompanyUnitAddressAsDefaultBilling($companyBusinessUnitTransfer, $companyUnitAddressCollectionTransfer);

        $companyBusinessUnitTransfer->setAddressCollection($companyUnitAddressCollectionTransfer);

        return $companyBusinessUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
     *
     * @return void
     */
    protected function markCompanyUnitAddressAsDefaultBilling(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
    ): void {
        foreach ($companyUnitAddressCollectionTransfer->getCompanyUnitAddresses() as $companyUnitAddressTransfer) {
            if ($companyBusinessUnitTransfer->getDefaultBillingAddress() !== $companyUnitAddressTransfer->getIdCompanyUnitAddress()) {
                continue;
            }

            $companyUnitAddressTransfer->setIsDefaultBilling(true);
        }
    }
}
