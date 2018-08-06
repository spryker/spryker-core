<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaFilterTransfer;

class CompanyUserExtractor
{
    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitBusinessFactory
     */
    protected $factory;

    /**
     * CompanyUserExtractor constructor.
     *
     * @param \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitBusinessFactory $factory
     */
    public function __construct(CompanyBusinessUnitBusinessFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitBusinessFactory
     */
    protected function getFactory()
    {
        return $this->factory;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer[]
     */
    public function getCompanyUserCollection(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer)
    {
        $companyUserCriteriaFilterTransfer = new CompanyUserCriteriaFilterTransfer();
        $companyUserCriteriaFilterTransfer->setIdCompany($companyBusinessUnitTransfer->getFkCompany());
        $companyUserCollectionTransfer = $this->getFactory()->getCompanyUser()->getCompanyUserCollection($companyUserCriteriaFilterTransfer);

        $businessUnitCompanyUserList = [];
        foreach ($companyUserCollectionTransfer->getCompanyUsers() as $companyUserTransfer) {
            if (in_array($companyUserTransfer->getFkCompanyBusinessUnit(), $companyBusinessUnitTransfer->getIdCompanyBusinessUnit())) {
                $businessUnitCompanyUserList[] = $companyUserTransfer;
            }
        }

        return $businessUnitCompanyUserList;
    }
}
