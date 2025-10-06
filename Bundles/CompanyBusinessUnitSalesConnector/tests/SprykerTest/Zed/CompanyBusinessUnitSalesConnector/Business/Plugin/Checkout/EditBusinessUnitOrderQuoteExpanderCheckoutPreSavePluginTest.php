<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitSalesConnector\Business\Plugin\Checkout;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Communication\Plugin\Checkout\EditBusinessUnitOrderQuoteExpanderCheckoutPreSavePlugin;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Communication\Plugin\Permission\EditBusinessUnitOrdersPermissionPlugin;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use SprykerTest\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnitSalesConnector
 * @group Business
 * @group Plugin
 * @group Checkout
 * @group EditBusinessUnitOrderQuoteExpanderCheckoutPreSavePluginTest
 * Add your own group annotations below this line
 */
class EditBusinessUnitOrderQuoteExpanderCheckoutPreSavePluginTest extends Unit
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
     * @uses \Spryker\Zed\CompanyBusinessUnitSalesConnector\Communication\Plugin\Permission\EditBusinessUnitOrdersPermissionPlugin::KEY
     *
     * @var string
     */
    protected const PERMISSION_KEY_EDIT_BUSINESS_UNIT_ORDERS = 'EditBusinessUnitOrdersPermissionPlugin';

    /**
     * @var string
     */
    protected const PERMISSION_KEY_SEE_BUSINESS_UNIT_ORDERS = 'SeeBusinessUnitOrdersPermissionPlugin';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorBusinessTester
     */
    protected CompanyBusinessUnitSalesConnectorBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION, [
            new EditBusinessUnitOrdersPermissionPlugin(),
        ]);
        $this->tester->preparePermissionStorageDependency(new PermissionStoragePlugin());
    }

    /**
     * @return void
     */
    public function testPreSaveExpandsQuoteWithOriginalOrderWhenUserHasPermissionAndOrderBelongsToBusinessUnit(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_BUSINESS_UNIT_ORDERS);
        $saveOrderTransfer = $this->tester->createOrderWithCompanyBusinessUnitUuid(static::ORDER_REFERENCE, $companyUserTransfer);
        $customerTransfer = (new CustomerTransfer())
            ->setCompanyUserTransfer($companyUserTransfer);
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer($customerTransfer)
            ->setAmendmentOrderReference($saveOrderTransfer->getOrderReference());

        // Act
        $resultQuoteTransfer = (new EditBusinessUnitOrderQuoteExpanderCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertNotNull($resultQuoteTransfer->getOriginalOrder());
        $this->assertSame($saveOrderTransfer->getOrderReference(), $resultQuoteTransfer->getOriginalOrder()->getOrderReference());
        $this->assertSame(
            $companyUserTransfer->getCompanyBusinessUnit()->getUuid(),
            $resultQuoteTransfer->getOriginalOrder()->getCompanyBusinessUnitUuid(),
        );
    }

    /**
     * @return void
     */
    public function testPreSaveDoesNothingWhenOriginalOrderIsAlreadySet(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_BUSINESS_UNIT_ORDERS);
        $saveOrderTransfer = $this->tester->createOrderWithCompanyBusinessUnitUuid(static::ORDER_REFERENCE, $companyUserTransfer);

        $originalOrderTransfer = new OrderTransfer();
        $originalOrderTransfer->setOrderReference('ORIGINAL-ORDER');

        $customerTransfer = (new CustomerTransfer())
            ->setCompanyUserTransfer($companyUserTransfer);

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer($customerTransfer)
            ->setAmendmentOrderReference($saveOrderTransfer->getOrderReference())
            ->setOriginalOrder($originalOrderTransfer);

        // Act
        $resultQuoteTransfer = (new EditBusinessUnitOrderQuoteExpanderCheckoutPreSavePlugin())->preSave($quoteTransfer);

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
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_SEE_BUSINESS_UNIT_ORDERS);
        $saveOrderTransfer = $this->tester->createOrderWithCompanyBusinessUnitUuid(static::ORDER_REFERENCE, $companyUserTransfer);

        $customerTransfer = (new CustomerTransfer())
            ->setCompanyUserTransfer($companyUserTransfer);

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer($customerTransfer)
            ->setAmendmentOrderReference($saveOrderTransfer->getOrderReference());

        // Act
        $resultQuoteTransfer = (new EditBusinessUnitOrderQuoteExpanderCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertNull($resultQuoteTransfer->getOriginalOrder());
    }

    /**
     * @return void
     */
    public function testPreSaveDoesNothingWhenOrderDoesNotBelongToBusinessUnit(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_BUSINESS_UNIT_ORDERS);
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
        $resultQuoteTransfer = (new EditBusinessUnitOrderQuoteExpanderCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertNull($resultQuoteTransfer->getOriginalOrder());
    }

    /**
     * @return void
     */
    public function testPreSaveDoesNothingWhenCompanyUserTransferIsNotProvided(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_BUSINESS_UNIT_ORDERS);
        $saveOrderTransfer = $this->tester->createOrderWithCompanyBusinessUnitUuid(static::ORDER_REFERENCE, $companyUserTransfer);
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(new CustomerTransfer())
            ->setAmendmentOrderReference($saveOrderTransfer->getOrderReference());

        // Act
        $resultQuoteTransfer = (new EditBusinessUnitOrderQuoteExpanderCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertNull($resultQuoteTransfer->getOriginalOrder());
    }

    /**
     * @return void
     */
    public function testPreSaveDoesNothingWhenOrderReferenceIsInvalid(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_BUSINESS_UNIT_ORDERS);
        $customerTransfer = (new CustomerTransfer())
            ->setCompanyUserTransfer($companyUserTransfer);
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer($customerTransfer)
            ->setAmendmentOrderReference('INVALID-ORDER-REFERENCE');

        // Act
        $resultQuoteTransfer = (new EditBusinessUnitOrderQuoteExpanderCheckoutPreSavePlugin())->preSave($quoteTransfer);

        // Assert
        $this->assertNull($resultQuoteTransfer->getOriginalOrder());
    }
}
