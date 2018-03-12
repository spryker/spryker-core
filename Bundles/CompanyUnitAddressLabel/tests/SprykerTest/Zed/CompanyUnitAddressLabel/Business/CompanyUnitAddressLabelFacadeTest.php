<?php

namespace SprykerTest\Zed\CompanyUnitAddressLabel\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanyUnitAddressLabel
 * @group Business
 * @group Facade
 * @group CompanyUnitAddressLabelFacadeTest
 * Add your own group annotations below this line
 */
class CompanyUnitAddressLabelFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUnitAddressLabel\CompanyUnitAddressLabelBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSaveLabelToAddressRelations(): void
    {
        $facade = $this->tester->getCompanyUnitAddressLabelFacade();
        $companyUnitAddressTransfer = $this->tester->haveCompanyUnitAddressTransfer();
        $facade->saveLabelToAddressRelations($companyUnitAddressTransfer);
    }

    /**
     * @return void
     */
    public function testHydrateCompanyUnitAddressWithLabelCollection(): void
    {
    }
}
