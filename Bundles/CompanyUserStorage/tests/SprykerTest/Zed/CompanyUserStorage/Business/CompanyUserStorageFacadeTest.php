<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUserStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\FilterTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUserStorage
 * @group Business
 * @group Facade
 * @group CompanyUserStorageFacadeTest
 * Add your own group annotations below this line
 */
class CompanyUserStorageFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUserStorage\CompanyUserStorageBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testGetCompanyUsersByFilter(): void
    {
        // Arrange
        $this->tester->ensureCompanyUserDatabaseTableIsEmpty();
        $companyUserTransfer = $this->tester->haveCompanyUserTransfer();

        // Act
        $companyUserTransfers = $this->tester->getFacade()->getCompanyUsersByFilter(new FilterTransfer());

        // Assert
        $this->assertNotEmpty($companyUserTransfers);
        $this->assertEquals($companyUserTransfer->getIdCompanyUser(), $companyUserTransfers[0]->getIdCompanyUser());
    }
}
