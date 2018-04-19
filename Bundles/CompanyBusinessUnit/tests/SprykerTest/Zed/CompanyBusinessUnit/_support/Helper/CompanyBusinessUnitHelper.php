<?php

namespace SprykerTest\Zed\CompanyBusinessUnit\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyBuilder;
use Generated\Shared\DataBuilder\CompanyBusinessUnitBuilder;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyBusinessUnitHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function haveCompanyBusinessUnit(array $seedData = [])
    {
        $companyBusinessUnit = (new CompanyBusinessUnitBuilder($seedData))->build();
        $companyBusinessUnit->setIdCompanyBusinessUnit(null);

        $this->getCompanyBusinessUnitFacade()->create($companyBusinessUnit);

        return $companyBusinessUnit;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function getCompany()
    {
        $companyTransfer = (new CompanyBuilder())->build();

        return $this->getLocator()->company()->facade()->create($companyTransfer)->getCompanyTransfer();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface
     */
    protected function getCompanyBusinessUnitFacade()
    {
        return $this->getLocator()->companyBusinessUnit()->facade();
    }
}
