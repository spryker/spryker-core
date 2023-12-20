<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddress\Business\Facade;

use Codeception\Test\Unit;
use SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUnitAddress
 * @group Business
 * @group Facade
 * @group UpdateTest
 * Add your own group annotations below this line
 */
class UpdateTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_ADDRESS = 'TEST ADDRESS';

    /**
     * @var \SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester
     */
    protected CompanyUnitAddressBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldPersistUpdatedDataToDatabase(): void
    {
        // Arrange
        $companyUnitAddressTransfer = $this->tester->haveCompanyUnitAddress();
        $companyUnitAddressTransfer->setAddress1(static::TEST_ADDRESS);

        // Act
        $companyUnitAddressResponseTransfer = $this->tester->getFacade()
            ->update($companyUnitAddressTransfer);

        // Assert
        $companyUnitAddressTransferLoaded = $this->tester->getFacade()
            ->findCompanyUnitAddressById($companyUnitAddressTransfer->getIdCompanyUnitAddress());

        $this->assertTrue($companyUnitAddressResponseTransfer->getIsSuccessful());
        $this->assertSame(static::TEST_ADDRESS, $companyUnitAddressTransferLoaded->getAddress1());
    }
}
