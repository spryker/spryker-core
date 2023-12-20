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
 * @group DeleteTest
 * Add your own group annotations below this line
 */
class DeleteTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester
     */
    protected CompanyUnitAddressBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldRemoveDataFromPersistence(): void
    {
        // Arrange
        $companyUnitAddressTransfer = $this->tester->haveCompanyUnitAddress();

        // Act
        $this->tester->getFacade()
            ->delete($companyUnitAddressTransfer);

        // Assert
        $this->assertNull(
            $this->tester->getFacade()
                ->findCompanyUnitAddressById($companyUnitAddressTransfer->getIdCompanyUnitAddress()),
        );
    }
}
