<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabelGui\Dependency\Facade;

use Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer;

class CompanyUnitAddressLabelGuiToCompanyUnitAddressLabelFacadeBridge implements CompanyUnitAddressLabelGuiToCompanyUnitAddressLabelFacadeInterface
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface
     */
    protected $companyUnitAddressLabelFacade;

    /**
     * @param \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface $companyUnitAddressLabelFacade
     */
    public function __construct($companyUnitAddressLabelFacade)
    {
        $this->companyUnitAddressLabelFacade = $companyUnitAddressLabelFacade;
    }

    /**
     * @param int $idCompanyUnitAddress
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressLabelCollectionTransfer
     */
    public function getCompanyUnitAddressLabelsByAddress(int $idCompanyUnitAddress): CompanyUnitAddressLabelCollectionTransfer
    {
        return $this->companyUnitAddressLabelFacade->getCompanyUnitAddressLabelsByAddress($idCompanyUnitAddress);
    }
}
