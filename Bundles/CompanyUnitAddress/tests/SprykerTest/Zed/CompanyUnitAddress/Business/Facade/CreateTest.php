<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddress\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CompanyUnitAddressBuilder;
use SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUnitAddress
 * @group Business
 * @group Facade
 * @group CreateTest
 * Add your own group annotations below this line
 */
class CreateTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester
     */
    protected CompanyUnitAddressBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldPersistDataToDatabase(): void
    {
        // Arrange
        $companyUnitAddressTransfer = (new CompanyUnitAddressBuilder())->build();

        // Act
        $companyUnitAddressTransfer = $this->tester->getFacade()
            ->create($companyUnitAddressTransfer)
            ->getCompanyUnitAddressTransfer();

        // Assert
        $this->assertNotEmpty($companyUnitAddressTransfer->getIdCompanyUnitAddress());
    }
}
