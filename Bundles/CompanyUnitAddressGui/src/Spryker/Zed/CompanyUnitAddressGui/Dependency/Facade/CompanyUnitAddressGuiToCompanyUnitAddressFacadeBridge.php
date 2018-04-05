<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Dependency\Facade;

use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

class CompanyUnitAddressGuiToCompanyUnitAddressFacadeBridge implements CompanyUnitAddressGuiToCompanyUnitAddressFacadeInterface
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface
     */
    protected $companyUnitAddressFacade;

    /**
     * @param \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface $companyUnitAddressFacade
     */
    public function __construct($companyUnitAddressFacade)
    {
        $this->companyUnitAddressFacade = $companyUnitAddressFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function getCompanyUnitAddressById(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressTransfer
    {
        return $this->companyUnitAddressFacade
            ->getCompanyUnitAddressById($companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function update($companyUnitAddressTransfer)
    {
        return $this->companyUnitAddressFacade->update($companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function create(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer
    {
        return $this->companyUnitAddressFacade->create($companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    public function getCompanyUnitAddressCollection(
        CompanyUnitAddressCriteriaFilterTransfer $criteriaFilterTransfer
    ): CompanyUnitAddressCollectionTransfer {

        return $this->companyUnitAddressFacade->getCompanyUnitAddressCollection($criteriaFilterTransfer);
    }
}
