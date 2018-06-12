<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitAssigner;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface;

class CompanyBusinessUnitAssigner implements CompanyBusinessUnitAssignerInterface
{
    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface $repository
     */
    public function __construct(CompanyBusinessUnitRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserResponseTransfer $companyUserResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function assignDefaultBusinessUnitToCompanyUser(
        CompanyUserResponseTransfer $companyUserResponseTransfer
    ): CompanyUserResponseTransfer {
        if ($companyUserResponseTransfer->getCompanyUser()->getIdCompanyUser() === null
            && $companyUserResponseTransfer->getCompanyUser()->getFkCompanyBusinessUnit() === null
        ) {
            $companyBusinessUnit = $this->repository
                ->findDefaultBusinessUnitByCompanyId($companyUserResponseTransfer->getCompanyUser()->getFkCompany());

            if ($companyBusinessUnit !== null) {
                $companyUserResponseTransfer->getCompanyUser()->setFkCompanyBusinessUnit(
                    $companyBusinessUnit->getIdCompanyBusinessUnit()
                );
            }
        }

        return $companyUserResponseTransfer;
    }
}
