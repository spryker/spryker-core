<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SharedCart\Business\SharedCartFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use Spryker\Zed\SharedCart\Communication\Plugin\QuotePermissionStoragePlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\WriteSharedCartPermissionPlugin;

/**
 * Auto-generated group annotations
 *
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
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $ownerCompanyUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $otherCompanyUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\SpyQuoteCompanyUserEntityTransfer
     */
    protected $quoteCompanyUserEntityTransfer1;

    /**
     * @var \Generated\Shared\Transfer\SpyQuoteCompanyUserEntityTransfer
     */
    protected $quoteCompanyUserEntityTransfer2;

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
            new WriteSharedCartPermissionPlugin(),
        ]);

        $this->tester->getLocator()->permission()->facade()->syncPermissionPlugins();

        $readOnlyPermissionGroup = $this->tester->haveQuotePermissionGroup('READ_ONLY', [
            ReadSharedCartPermissionPlugin::KEY,
        ]);
        $fullAccessPermissionGroup = $this->tester->haveQuotePermissionGroup('FULL_ACCESS', [
            ReadSharedCartPermissionPlugin::KEY,
            WriteSharedCartPermissionPlugin::KEY,
        ]);

        $companyTransfer = $this->tester->haveCompany();

        $ownerCustomerTransfer = $this->tester->haveCustomer();
        $this->ownerCompanyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $ownerCustomerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $otherCustomerTransfer = $this->tester->haveCustomer();
        $this->otherCompanyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $otherCustomerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $quoteTransfer1 = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $ownerCustomerTransfer,
        ]);
        $quoteTransfer2 = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $ownerCustomerTransfer,
        ]);

        $this->quoteCompanyUserEntityTransfer1 = $this->tester->haveQuoteCompanyUser(
            $this->otherCompanyUserTransfer,
            $quoteTransfer1,
            $readOnlyPermissionGroup
        );
        $this->quoteCompanyUserEntityTransfer2 = $this->tester->haveQuoteCompanyUser(
            $this->otherCompanyUserTransfer,
            $quoteTransfer2,
            $fullAccessPermissionGroup
        );
    }

    /**
     * @return void
     */
    public function testOwnerCanReadCart1()
    {
        $ownerCanReadCart1 = $this->tester->getLocator()
            ->permission()
            ->facade()
            ->can(ReadSharedCartPermissionPlugin::KEY, $this->ownerCompanyUserTransfer->getIdCompanyUser(), $this->quoteCompanyUserEntityTransfer1->getFkQuote());

        $this->assertTrue($ownerCanReadCart1, 'Owner should have been able to read cart #1.');
    }

    /**
     * @return void
     */
    public function testOwnerCanWriteCart1()
    {
        $ownerCanWriteCart1 = $this->tester->getLocator()
            ->permission()
            ->facade()
            ->can(WriteSharedCartPermissionPlugin::KEY, $this->ownerCompanyUserTransfer->getIdCompanyUser(), $this->quoteCompanyUserEntityTransfer1->getFkQuote());

        $this->assertTrue($ownerCanWriteCart1, 'Owner should have been able to write cart #1.');
    }

    /**
     * @return void
     */
    public function testOtherUserCanReadCart1()
    {
        $otherUserCanReadCart1 = $this->tester->getLocator()
            ->permission()
            ->facade()
            ->can(ReadSharedCartPermissionPlugin::KEY, $this->otherCompanyUserTransfer->getIdCompanyUser(), $this->quoteCompanyUserEntityTransfer1->getFkQuote());

        $this->assertTrue($otherUserCanReadCart1, 'User should have been able to read cart #1.');
    }

    /**
     * @return void
     */
    public function testOtherUserCanNotWriteCart1()
    {
        $otherUserCanNotWriteCart1 = $this->tester->getLocator()
            ->permission()
            ->facade()
            ->can(WriteSharedCartPermissionPlugin::KEY, $this->otherCompanyUserTransfer->getIdCompanyUser(), $this->quoteCompanyUserEntityTransfer1->getFkQuote());

        $this->assertFalse($otherUserCanNotWriteCart1, 'User should NOT have been able to write cart #1.');
    }

    /**
     * @return void
     */
    public function testOwnerCanReadCart2()
    {
        $ownerCanReadCart2 = $this->tester->getLocator()
            ->permission()
            ->facade()
            ->can(ReadSharedCartPermissionPlugin::KEY, $this->ownerCompanyUserTransfer->getIdCompanyUser(), $this->quoteCompanyUserEntityTransfer1->getFkQuote());

        $this->assertTrue($ownerCanReadCart2, 'Owner should have been able to read cart #2.');
    }

    /**
     * @return void
     */
    public function testOwnerCanWriteCart2()
    {
        $ownerCanWriteCart2 = $this->tester->getLocator()
            ->permission()
            ->facade()
            ->can(WriteSharedCartPermissionPlugin::KEY, $this->ownerCompanyUserTransfer->getIdCompanyUser(), $this->quoteCompanyUserEntityTransfer2->getFkQuote());

        $this->assertTrue($ownerCanWriteCart2, 'Owner should have been able to write cart #2.');
    }

    /**
     * @return void
     */
    public function testOtherUserCanReadCart2()
    {
        $otherUserCanReadCart2 = $this->tester->getLocator()
            ->permission()
            ->facade()
            ->can(ReadSharedCartPermissionPlugin::KEY, $this->otherCompanyUserTransfer->getIdCompanyUser(), $this->quoteCompanyUserEntityTransfer2->getFkQuote());

        $this->assertTrue($otherUserCanReadCart2, 'Owner should have been able to read cart #2.');
    }

    /**
     * @return void
     */
    public function testOtherUserCanWriteCart2()
    {
        $otherUserCanWriteCart2 = $this->tester->getLocator()
            ->permission()
            ->facade()
            ->can(WriteSharedCartPermissionPlugin::KEY, $this->otherCompanyUserTransfer->getIdCompanyUser(), $this->quoteCompanyUserEntityTransfer2->getFkQuote());

        $this->assertTrue($otherUserCanWriteCart2, 'Owner should have been able to write cart #2.');
    }
}
