<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SharedCartsRestApi\Communication\Plugin;

use Codeception\TestCase\Test;
use Spryker\Zed\SharedCart\Communication\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Zed\SharedCartsRestApi\Communication\Plugin\CartsRestApi\QuotePermissionGroupQuoteExpanderPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SharedCartsRestApi
 * @group Communication
 * @group Plugin
 * @group QuotePermissionGroupQuoteExpanderPluginTest
 * Add your own group annotations below this line
 */
class QuotePermissionGroupQuoteExpanderPluginTest extends Test
{
    /**
     * @var \SprykerTest\Zed\SharedCartsRestApi\QuotePermissionGroupQuoteExpanderPluginTester
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
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $ownerCustomerTransfer;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $otherCustomerTransfer;

    /**
     * @var \Spryker\Zed\SharedCartsRestApi\Communication\Plugin\CartsRestApi\QuotePermissionGroupQuoteExpanderPlugin
     */
    protected $quotePermissionGroupQuoteExpanderPlugin;

    /**
     * @var \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer
     */
    protected $quotePermissionGroupEntityTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setPermissionDependencies();

        $this->quotePermissionGroupQuoteExpanderPlugin = new QuotePermissionGroupQuoteExpanderPlugin();

        $this->quotePermissionGroupEntityTransfer = $this->tester->haveQuotePermissionGroup('READ_ONLY', [
            ReadSharedCartPermissionPlugin::KEY,
        ]);

        $companyTransfer = $this->tester->haveCompany();

        $this->ownerCustomerTransfer = $this->tester->haveCustomerWithCompanyUser($companyTransfer);
        $this->ownerCustomerTransfer->setCompanyUserTransfer(null);
        $this->otherCustomerTransfer = $this->tester->haveCustomerWithCompanyUser($companyTransfer);

        $this->quoteTransfer = $this->tester->haveSharedQuote(
            $this->ownerCustomerTransfer,
            $this->otherCustomerTransfer->getCompanyUserTransfer(),
            $this->quotePermissionGroupEntityTransfer
        );
    }

    /**
     * @return void
     */
    public function testQuotePermissionGroupQuoteExpanderPluginShouldExpandQuoteWithQuotePermissionGroup(): void
    {
        // Assign
        $this->quoteTransfer->setCustomer($this->otherCustomerTransfer);

        // Act
        $quoteTransfer = $this->quotePermissionGroupQuoteExpanderPlugin->expandQuote($this->quoteTransfer);

        // Assert
        $this->assertNotNull($quoteTransfer->getQuotePermissionGroup());
        $this->assertEquals(
            $quoteTransfer->getQuotePermissionGroup()->getIdQuotePermissionGroup(),
            $this->quotePermissionGroupEntityTransfer->getIdQuotePermissionGroup()
        );
    }

    /**
     * @return void
     */
    public function testExpandQuoteWithQuotePermissionGroupShouldDoNothingIfCustomerIsNotCompanyUser(): void
    {
        // Assign
        $this->otherCustomerTransfer->setCompanyUserTransfer(null);
        $this->quoteTransfer->setCustomer($this->otherCustomerTransfer);

        // Act
        $quoteTransfer = $this->quotePermissionGroupQuoteExpanderPlugin->expandQuote($this->quoteTransfer);

        // Assert
        $this->assertNull($quoteTransfer->getQuotePermissionGroup());
    }

    /**
     * @return void
     */
    public function testExpandQuoteWithQuotePermissionGroupShouldDoNothingIfCustomerIsCartOwner(): void
    {
        // Assign
        $this->quoteTransfer->setCustomer($this->ownerCustomerTransfer);

        // Act
        $quoteTransfer = $this->quotePermissionGroupQuoteExpanderPlugin->expandQuote($this->quoteTransfer);

        // Assert
        $this->assertNull($quoteTransfer->getQuotePermissionGroup());
    }
}
