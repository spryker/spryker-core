<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SharedCartsRestApi\Business;

use Codeception\TestCase\Test;
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
 * @group SharedCartsRestApi
 * @group Business
 * @group Facade
 * @group SharedCartsRestApiFacadeTest
 * Add your own group annotations below this line
 */
class SharedCartsRestApiFacadeTest extends Test
{
    /**
     * @var \SprykerTest\Zed\SharedCartsRestApi\SharedCartsRestApiFacadeTester
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
    protected $quoteCompanyUserEntityTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
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
    }

    /**
     * @return void
     */
    public function testGetSharedCartsByCartUuidShouldReturnShareData(): void
    {
        // Assign
        /** @var \Spryker\Zed\SharedCartsRestApi\Business\SharedCartsRestApiFacadeInterface $sharedCartsRestApiFacade */
        $sharedCartsRestApiFacade = $this->tester->getFacade();
        $readOnlyPermissionGroup = $this->tester->haveQuotePermissionGroup('READ_ONLY', [
            ReadSharedCartPermissionPlugin::KEY,
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

        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $ownerCustomerTransfer,
        ]);

        $this->quoteCompanyUserEntityTransfer = $this->tester->haveQuoteCompanyUser(
            $this->otherCompanyUserTransfer,
            $quoteTransfer,
            $readOnlyPermissionGroup
        );

        // Act
        $shareDetailCollectionTransfer = $sharedCartsRestApiFacade->getSharedCartsByCartUuid($quoteTransfer);

        // Assert
        $this->assertCount(1, $shareDetailCollectionTransfer->getShareDetails());
        $shareDetailTransfer = $shareDetailCollectionTransfer->getShareDetails()[0];

        $this->assertEquals($this->otherCompanyUserTransfer->getUuid(), $shareDetailTransfer->getCompanyUserUuid());
        $this->assertNotNull($shareDetailTransfer->getUuid());
        $this->assertEquals($readOnlyPermissionGroup->getIdQuotePermissionGroup(), $shareDetailTransfer->getQuotePermissionGroup()->getIdQuotePermissionGroup());
    }
}
