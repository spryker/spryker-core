<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitSalesConnector\Business\Plugin\CartReorder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Communication\Plugin\CartReorder\EditBusinessUnitOrderCartReorderOrderProviderPlugin;
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
 * @group CartReorder
 * @group EditBusinessUnitOrderCartReorderOrderProviderPluginTest
 * Add your own group annotations below this line
 */
class EditBusinessUnitOrderCartReorderOrderProviderPluginTest extends Unit
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
    public function testFindOrderReturnsOrderWhenUserHasPermissionAndOrderBelongsToBusinessUnit(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_BUSINESS_UNIT_ORDERS);
        $saveOrderTransfer = $this->tester->createOrderWithCompanyBusinessUnitUuid(static::ORDER_REFERENCE, $companyUserTransfer);

        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setIsAmendment(true)
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $orderTransfer = (new EditBusinessUnitOrderCartReorderOrderProviderPlugin())->findOrder($cartReorderRequestTransfer);

        // Assert
        $this->assertNotNull($orderTransfer);
        $this->assertSame($saveOrderTransfer->getOrderReference(), $orderTransfer->getOrderReference());
        $this->assertSame(
            $companyUserTransfer->getCompanyBusinessUnit()->getUuid(),
            $orderTransfer->getCompanyBusinessUnitUuid(),
        );
    }

    /**
     * @return void
     */
    public function testFindOrderReturnsNullWhenIsAmendmentIsNotTrue(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_BUSINESS_UNIT_ORDERS);
        $saveOrderTransfer = $this->tester->createOrderWithCompanyBusinessUnitUuid(static::ORDER_REFERENCE, $companyUserTransfer);

        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $orderTransfer = (new EditBusinessUnitOrderCartReorderOrderProviderPlugin())->findOrder($cartReorderRequestTransfer);

        // Assert
        $this->assertNull($orderTransfer);
    }

    /**
     * @return void
     */
    public function testFindOrderReturnsNullWhenUserDoesNotHavePermission(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_SEE_BUSINESS_UNIT_ORDERS);
        $saveOrderTransfer = $this->tester->createOrderWithCompanyBusinessUnitUuid(static::ORDER_REFERENCE, $companyUserTransfer);

        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setIsAmendment(true)
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $orderTransfer = (new EditBusinessUnitOrderCartReorderOrderProviderPlugin())->findOrder($cartReorderRequestTransfer);

        // Assert
        $this->assertNull($orderTransfer);
    }

    /**
     * @return void
     */
    public function testFindOrderReturnsNullWhenOrderDoesNotBelongToBusinessUnit(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_BUSINESS_UNIT_ORDERS);
        $saveOrderTransfer = $this->tester->haveOrder([
            OrderTransfer::ORDER_REFERENCE => static::ORDER_REFERENCE,
            OrderTransfer::CUSTOMER => $companyUserTransfer->getCustomer()->toArray(),
        ], static::DEFAULT_OMS_PROCESS_NAME);
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setIsAmendment(true)
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $orderTransfer = (new EditBusinessUnitOrderCartReorderOrderProviderPlugin())->findOrder($cartReorderRequestTransfer);

        // Assert
        $this->assertNull($orderTransfer);
    }

    /**
     * @return void
     */
    public function testFindOrderReturnsNullWhenOrderReferenceIsInvalid(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_BUSINESS_UNIT_ORDERS);
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setIsAmendment(true)
            ->setOrderReference('INVALID-ORDER-REFERENCE')
            ->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $orderTransfer = (new EditBusinessUnitOrderCartReorderOrderProviderPlugin())->findOrder($cartReorderRequestTransfer);

        // Assert
        $this->assertNull($orderTransfer);
    }

    /**
     * @return void
     */
    public function testFindOrderReturnsNullWhenCompanyUserIsNotProvided(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_BUSINESS_UNIT_ORDERS);
        $saveOrderTransfer = $this->tester->createOrderWithCompanyBusinessUnitUuid(static::ORDER_REFERENCE, $companyUserTransfer);

        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setIsAmendment(true)
            ->setOrderReference($saveOrderTransfer->getOrderReference());

        // Act
        $orderTransfer = (new EditBusinessUnitOrderCartReorderOrderProviderPlugin())->findOrder($cartReorderRequestTransfer);

        // Assert
        $this->assertNull($orderTransfer);
    }
}
