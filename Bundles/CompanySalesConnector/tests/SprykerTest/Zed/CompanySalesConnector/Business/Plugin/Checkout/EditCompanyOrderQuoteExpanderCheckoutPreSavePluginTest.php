<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanySalesConnector\Business\Plugin\Checkout;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;
use Spryker\Zed\CompanySalesConnector\Communication\Plugin\Checkout\EditCompanyOrderQuoteExpanderCheckoutPreSavePlugin;
use Spryker\Zed\CompanySalesConnector\Communication\Plugin\Permission\EditCompanyOrdersPermissionPlugin;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use SprykerTest\Zed\CompanySalesConnector\CompanySalesConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanySalesConnector
 * @group Business
 * @group Plugin
 * @group Checkout
 * @group EditCompanyOrderQuoteExpanderCheckoutPreSavePluginTest
 * Add your own group annotations below this line
 */
class EditCompanyOrderQuoteExpanderCheckoutPreSavePluginTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var string
     */
    protected const ORDER_REFERENCE = 'TEST-ORDER-REF';

    /**
     * @uses \Spryker\Zed\CompanySalesConnector\Communication\Plugin\Permission\EditCompanyOrdersPermissionPlugin::KEY
     *
     * @var string
     */
    protected const PERMISSION_KEY_EDIT_COMPANY_ORDERS = 'EditCompanyOrdersPermissionPlugin';

    /**
     * @uses \Spryker\Zed\CompanySalesConnector\Communication\Plugin\Permission\SeeCompanyOrdersPermissionPlugin::KEY
     *
     * @var string
     */
    protected const PERMISSION_KEY_SEE_COMPANY_ORDERS = 'SeeCompanyOrdersPermissionPlugin';

    /**
     * @var \SprykerTest\Zed\CompanySalesConnector\CompanySalesConnectorBusinessTester
     */
    protected CompanySalesConnectorBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION, [
            new EditCompanyOrdersPermissionPlugin(),
        ]);
        $this->tester->preparePermissionStorageDependency(new PermissionStoragePlugin());
    }

    /**
     * @return void
     */
    public function testPreSaveExpandsQuoteWithOriginalOrderWhenUserHasPermissionAndOrderBelongsToCompany(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_COMPANY_ORDERS);
        $saveOrderTransfer = $this->tester->createOrderWithCompanyUuid(static::ORDER_REFERENCE, $companyUserTransfer);
        $customerTransfer = (new CustomerTransfer())
            ->setCompanyUserTransfer($companyUserTransfer);
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer($customerTransfer)
            ->setAmendmentOrderReference($saveOrderTransfer->getOrderReference());

        // Act
        $resultQuoteTransfer = (new EditCompanyOrderQuoteExpanderCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertNotNull($resultQuoteTransfer->getOriginalOrder());
        $this->assertSame($saveOrderTransfer->getOrderReference(), $resultQuoteTransfer->getOriginalOrder()->getOrderReference());
        $this->assertSame($companyUserTransfer->getCompanyOrFail()->getUuid(), $resultQuoteTransfer->getOriginalOrder()->getCompanyUuid());
    }

    /**
     * @return void
     */
    public function testPreSaveDoesNothingWhenOriginalOrderIsAlreadySet(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_COMPANY_ORDERS);
        $saveOrderTransfer = $this->tester->createOrderWithCompanyUuid(static::ORDER_REFERENCE, $companyUserTransfer);

        $originalOrderTransfer = new OrderTransfer();
        $originalOrderTransfer->setOrderReference('ORIGINAL-ORDER');

        $customerTransfer = (new CustomerTransfer())
            ->setCompanyUserTransfer($companyUserTransfer);

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer($customerTransfer)
            ->setAmendmentOrderReference($saveOrderTransfer->getOrderReference())
            ->setOriginalOrder($originalOrderTransfer);

        // Act
        $resultQuoteTransfer = (new EditCompanyOrderQuoteExpanderCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertNotNull($resultQuoteTransfer->getOriginalOrder());
        $this->assertSame('ORIGINAL-ORDER', $resultQuoteTransfer->getOriginalOrder()->getOrderReference());
    }

    /**
     * @return void
     */
    public function testPreSaveDoesNothingWhenUserDoesNotHavePermission(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_SEE_COMPANY_ORDERS);
        $saveOrderTransfer = $this->tester->createOrderWithCompanyUuid(static::ORDER_REFERENCE, $companyUserTransfer);

        $customerTransfer = (new CustomerTransfer())
            ->setCompanyUserTransfer($companyUserTransfer);

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer($customerTransfer)
            ->setAmendmentOrderReference($saveOrderTransfer->getOrderReference());

        // Act
        $resultQuoteTransfer = (new EditCompanyOrderQuoteExpanderCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertNull($resultQuoteTransfer->getOriginalOrder());
    }

    /**
     * @return void
     */
    public function testPreSaveDoesNothingWhenOrderDoesNotBelongToCompany(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_COMPANY_ORDERS);
        $saveOrderTransfer = $this->tester->haveOrder([
            OrderTransfer::ORDER_REFERENCE => static::ORDER_REFERENCE,
            OrderTransfer::CUSTOMER => $companyUserTransfer->getCustomer()->toArray(),
        ], static::DEFAULT_OMS_PROCESS_NAME);

        $customerTransfer = (new CustomerTransfer())
            ->setCompanyUserTransfer($companyUserTransfer);

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer($customerTransfer)
            ->setAmendmentOrderReference($saveOrderTransfer->getOrderReference());

        // Act
        $resultQuoteTransfer = (new EditCompanyOrderQuoteExpanderCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertNull($resultQuoteTransfer->getOriginalOrder());
    }

    /**
     * @return void
     */
    public function testPreSaveDoesNothingWhenCompanyUserTransferIsNotProvided(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_COMPANY_ORDERS);
        $saveOrderTransfer = $this->tester->createOrderWithCompanyUuid(static::ORDER_REFERENCE, $companyUserTransfer);
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(new CustomerTransfer())
            ->setAmendmentOrderReference($saveOrderTransfer->getOrderReference());

        // Act
        $resultQuoteTransfer = (new EditCompanyOrderQuoteExpanderCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertNull($resultQuoteTransfer->getOriginalOrder());
    }

    /**
     * @return void
     */
    public function testPreSaveDoesNothingWhenOrderReferenceIsInvalid(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_COMPANY_ORDERS);
        $customerTransfer = (new CustomerTransfer())
            ->setCompanyUserTransfer($companyUserTransfer);
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer($customerTransfer)
            ->setAmendmentOrderReference('INVALID-ORDER-REFERENCE');

        // Act
        $resultQuoteTransfer = (new EditCompanyOrderQuoteExpanderCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertNull($resultQuoteTransfer->getOriginalOrder());
    }
}
