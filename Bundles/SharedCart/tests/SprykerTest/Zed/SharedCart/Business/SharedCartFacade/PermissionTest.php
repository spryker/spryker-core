<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SharedCart\Business\SharedCartFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use Spryker\Zed\SharedCart\Communication\Plugin\QuotePermissionStoragePlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\WriteharedCartPermissionPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group SharedCart
 * @group Business
 * @group SharedCartFacade
 * @group PermissionTest
 * Add your own group annotations below this line
 */
class PermissionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SharedCart\SharedCartBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION_STORAGE, [
            new QuotePermissionStoragePlugin(),
        ]);

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION, [
            new ReadSharedCartPermissionPlugin(),
            new WriteharedCartPermissionPlugin(),
        ]);

        $this->tester->getLocator()->permission()->facade()->syncPermissionPlugins();
    }

    /**
     * @return void
     */
    public function testQuotePermissionCheck()
    {
        // Arrange
        $readOnlyPermissionGroup = $this->tester->haveQuotePermissionGroup('READ_ONLY', [
            ReadSharedCartPermissionPlugin::KEY,
        ]);
        $fullAccessPermissionGroup = $this->tester->haveQuotePermissionGroup('FULL_ACCESS', [
            ReadSharedCartPermissionPlugin::KEY,
            WriteharedCartPermissionPlugin::KEY,
        ]);

        $companyTransfer = $this->tester->haveCompany();
        $customerTransfer = $this->tester->haveCustomer();
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
        $quoteTransfer1 = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $quoteTransfer2 = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);

        $quoteCompanyUserEntityTransfer1 = $this->tester->haveQuoteCompanyUser(
            $companyUserTransfer,
            $quoteTransfer1,
            $readOnlyPermissionGroup
        );
        $quoteCompanyUserEntityTransfer2 = $this->tester->haveQuoteCompanyUser(
            $companyUserTransfer,
            $quoteTransfer2,
            $fullAccessPermissionGroup
        );

        // Act
        $userCanReadCart1 = $this->tester->getLocator()
            ->permission()
            ->facade()
            ->can(ReadSharedCartPermissionPlugin::KEY, $quoteCompanyUserEntityTransfer1->getFkCompanyUser(), $quoteCompanyUserEntityTransfer1->getFkQuote());

        $userCanNotWriteCart1 = $this->tester->getLocator()
            ->permission()
            ->facade()
            ->can(WriteharedCartPermissionPlugin::KEY, $quoteCompanyUserEntityTransfer1->getFkCompanyUser(), $quoteCompanyUserEntityTransfer1->getFkQuote());

        $userCanReadCart2 = $this->tester->getLocator()
            ->permission()
            ->facade()
            ->can(ReadSharedCartPermissionPlugin::KEY, $quoteCompanyUserEntityTransfer2->getFkCompanyUser(), $quoteCompanyUserEntityTransfer2->getFkQuote());

        $userCanWriteCart2 = $this->tester->getLocator()
            ->permission()
            ->facade()
            ->can(WriteharedCartPermissionPlugin::KEY, $quoteCompanyUserEntityTransfer2->getFkCompanyUser(), $quoteCompanyUserEntityTransfer2->getFkQuote());

        // Assert
        $this->assertTrue($userCanReadCart1, 'User should have been able to read cart #1.');
        $this->assertFalse($userCanNotWriteCart1, 'User should NOT have been able to write cart #1.');
        $this->assertTrue($userCanReadCart2, 'User should have been able to read cart #2.');
        $this->assertTrue($userCanWriteCart2, 'User should have been able to write cart #2.');
    }
}
