<?php

namespace SprykerTest\Zed\CompanyBusinessUnit\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyBuilder;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyBusinessUnitHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    public function getCompany()
    {
        $companyTransfer = (new CompanyBuilder())->build();

        return $this->getLocator()->company()->facade()->create($companyTransfer)->getCompanyTransfer();
    }
}
