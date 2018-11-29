<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddress\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanyUnitAddress
 * @group Business
 * @group Facade
 * @group CompanyUnitAddressFacadeTest
 * Add your own group annotations below this line
 */
class CompanyUnitAddressFacadeTest extends Unit
{
    protected const COMPANY_ADDRESS_KEY = 'Address--1';
    protected const COMPANY_BUSINESS_UNIT_KEY = 'Test_HQ';

    /**
     * @var \SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface
     */
    protected $companyUnitAddressFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->companyUnitAddressFacade = $this->tester->getLocator()->companyUnitAddress()->facade();
    }

    /**
     * @return void
     */
    public function testExpandCompanyBusinessUnitWithCompanyUnitAddressCollection(): void
    {
        // Assign
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::KEY => static::COMPANY_BUSINESS_UNIT_KEY,
        ]);

        $companyBusinessUnitCollectionTransfer = new CompanyBusinessUnitCollectionTransfer();
        $companyBusinessUnitCollectionTransfer->addCompanyBusinessUnit($companyBusinessUnitTransfer);

        $this->tester->haveCompanyUnitAddress([
            CompanyUnitAddressTransfer::KEY => static::COMPANY_ADDRESS_KEY,
            CompanyUnitAddressTransfer::COMPANY_BUSINESS_UNITS => $companyBusinessUnitCollectionTransfer,
        ]);

        // Act
        $companyBusinessUnitTransfer = $this->companyUnitAddressFacade->expandCompanyBusinessUnitWithCompanyUnitAddressCollection($companyBusinessUnitTransfer);

        // Assert
        $this->assertInstanceOf(CompanyUnitAddressCollectionTransfer::class, $companyBusinessUnitTransfer->getAddressCollection());
        $this->assertCount(1, $companyBusinessUnitTransfer->getAddressCollection()->getCompanyUnitAddresses());
    }
}
