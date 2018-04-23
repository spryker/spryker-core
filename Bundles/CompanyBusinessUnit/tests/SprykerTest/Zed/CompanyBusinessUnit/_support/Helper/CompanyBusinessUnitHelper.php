<?php

namespace SprykerTest\Zed\CompanyBusinessUnit\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyBuilder;
use Generated\Shared\DataBuilder\CompanyBusinessUnitBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyBusinessUnitHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function haveCompanyBusinessUnit(array $seedData = []): CompanyBusinessUnitTransfer
    {
        $companyBusinessUnitTransfer = (new CompanyBusinessUnitBuilder($seedData))->build();
        $companyBusinessUnitTransfer->setIdCompanyBusinessUnit(null);

        $companyBusinessUnitTransfer = $this->getCompanyBusinessUnitFacade()
            ->create($companyBusinessUnitTransfer)
            ->getCompanyBusinessUnitTransfer();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($companyBusinessUnitTransfer) {
            $this->getCompanyBusinessUnitFacade()->delete($companyBusinessUnitTransfer);
        });

        return $companyBusinessUnitTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function getCompany(): CompanyTransfer
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
