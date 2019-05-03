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
     * @var \SprykerTest\Zed\SharedCartsRestApi\SharedCartsRestApiBusinessTester
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
    }

    public function testGetSharedCartsByCartUuid(): void
    {
        // Assign
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

        $quoteTransfer1 = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $ownerCustomerTransfer,
        ]);

        $this->quoteCompanyUserEntityTransfer1 = $this->tester->haveQuoteCompanyUser(
            $this->otherCompanyUserTransfer,
            $quoteTransfer1,
            $readOnlyPermissionGroup
        );

        // Act
        $shareDetailCollectionTransfer = $this->tester->getFacade()->getSharedCartsByCartUuid($quoteTransfer1);

        // Assert
//        $this->assertEquals($customerTransfer->getCompanyUserTransfer()->getUuid(), $expandedCustomerIdentifierTransfer->getIdCompanyUser());
    }
}
