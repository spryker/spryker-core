<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Business\CompanyBusinessUnit;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface;

class CompanyBusinessUnitGuiReader implements CompanyBusinessUnitGuiReaderInterface
{
    /**
     * @var \Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface
     */
    protected $businessUnitFacade;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface $businessUnitFacade
     */
    public function __construct(CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface $businessUnitFacade)
    {
        $this->businessUnitFacade = $businessUnitFacade;
    }

    /**
     * @param int $idCompanyUser
     *
     * @return string|null
     */
    public function findCompanyBusinessUnitNameByIdCompanyUser(int $idCompanyUser): ?string
    {
        $companyBusinessUnitName = null;
        $companyBusinessUnitFilter = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setIdCompanyUser($idCompanyUser);

        $companyBusinessUnits = $this->businessUnitFacade
            ->getCompanyBusinessUnitCollection($companyBusinessUnitFilter)
            ->getCompanyBusinessUnits();

        if ($companyBusinessUnits->count() > 0) {
            $companyBusinessUnitName = $companyBusinessUnits->offsetGet(0)->getName();
        }

        return $companyBusinessUnitName;
    }
}
