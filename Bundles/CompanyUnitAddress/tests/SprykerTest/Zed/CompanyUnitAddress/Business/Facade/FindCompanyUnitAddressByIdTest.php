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
 * @group FindCompanyUnitAddressByIdTest
 * Add your own group annotations below this line
 */
class FindCompanyUnitAddressByIdTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester
     */
    protected CompanyUnitAddressBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldReturnTransferWhenAddressExists(): void
    {
        // Arrange
        $companyUnitAddressTransfer = $this->tester->haveCompanyUnitAddress();

        // Act
        $companyUnitAddressTransferLoaded = $this->tester->getFacade()
            ->findCompanyUnitAddressById($companyUnitAddressTransfer->getIdCompanyUnitAddress());

        // Assert
        $this->assertNotNull($companyUnitAddressTransferLoaded);
    }

    /**
     * @return void
     */
    public function testShouldReturnNullWhenAddressDoesNotExist(): void
    {
        // Arrange
        $idCompanyUnitAddress = 0;

        // Act
        $companyUnitAddressTransferLoaded = $this->tester->getFacade()
            ->findCompanyUnitAddressById($idCompanyUnitAddress);

        // Assert
        $this->assertNull($companyUnitAddressTransferLoaded);
    }
}
