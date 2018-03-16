<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyGui\Communication\Form\CompanyForm;
use Spryker\Zed\CompanyGui\Dependency\Facade\CompanyGuiToCompanyFacadeInterface;

class CompanyBusinessUnitFormDataProvider
{
    /**
     * @var CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @param CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     */
    public function __construct(CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade)
    {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function getData(int $idCompanyBusinessUnit)
    {
        return $this->companyBusinessUnitFacade->getCompanyBusinessUnitById($this->createCompanyBusinessUnitTransfer()->setIdCompanyBusinessUnit($idCompanyBusinessUnit));
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return array
     */
    public function getOptions(int $idCompanyBusinessUnit)
    {
        return [];
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    protected function createCompanyBusinessUnitTransfer()
    {
        return new CompanyBusinessUnitTransfer();
    }
}
