<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanySalesConnector\Business\Plugin\CartReorderRestApi;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;
use Spryker\Zed\CompanySalesConnector\Communication\Plugin\CartReorder\EditCompanyOrderCartReorderOrderProviderPlugin;
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
 * @group CartReorderRestApi
 * @group EditCompanyOrderCartReorderOrderProviderPluginTest
 * Add your own group annotations below this line
 */
class EditCompanyOrderCartReorderOrderProviderPluginTest extends Unit
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
    public function testFindOrderReturnsOrderWhenUserHasPermissionAndOrderBelongsToCompany(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_COMPANY_ORDERS);
        $saveOrderTransfer = $this->tester->createOrderWithCompanyUuid(static::ORDER_REFERENCE, $companyUserTransfer);

        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setIsAmendment(true)
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $orderTransfer = (new EditCompanyOrderCartReorderOrderProviderPlugin())->findOrder($cartReorderRequestTransfer);

        // Assert
        $this->assertNotNull($orderTransfer);
        $this->assertSame($saveOrderTransfer->getOrderReference(), $orderTransfer->getOrderReference());
        $this->assertSame($companyUserTransfer->getCompanyOrFail()->getUuid(), $orderTransfer->getCompanyUuid());
    }

    /**
     * @return void
     */
    public function testFindOrderReturnsNullWhenIsAmendmentIsNotTrue(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_COMPANY_ORDERS);
        $saveOrderTransfer = $this->tester->createOrderWithCompanyUuid(static::ORDER_REFERENCE, $companyUserTransfer);

        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $orderTransfer = (new EditCompanyOrderCartReorderOrderProviderPlugin())->findOrder($cartReorderRequestTransfer);

        // Assert
        $this->assertNull($orderTransfer);
    }

    /**
     * @return void
     */
    public function testFindOrderReturnsNullWhenUserDoesNotHavePermission(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_SEE_COMPANY_ORDERS);
        $saveOrderTransfer = $this->tester->createOrderWithCompanyUuid(static::ORDER_REFERENCE, $companyUserTransfer);

        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setIsAmendment(true)
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $orderTransfer = (new EditCompanyOrderCartReorderOrderProviderPlugin())->findOrder($cartReorderRequestTransfer);

        // Assert
        $this->assertNull($orderTransfer);
    }

    /**
     * @return void
     */
    public function testFindOrderReturnsNullWhenOrderDoesNotBelongToCompany(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_COMPANY_ORDERS);
        $saveOrderTransfer = $this->tester->haveOrder([
            OrderTransfer::ORDER_REFERENCE => static::ORDER_REFERENCE,
            OrderTransfer::CUSTOMER => $companyUserTransfer->getCustomer()->toArray(),
        ], static::DEFAULT_OMS_PROCESS_NAME);

        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setIsAmendment(true)
            ->setOrderReference($saveOrderTransfer->getOrderReference())
            ->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $orderTransfer = (new EditCompanyOrderCartReorderOrderProviderPlugin())->findOrder($cartReorderRequestTransfer);

        // Assert
        $this->assertNull($orderTransfer);
    }

    /**
     * @return void
     */
    public function testFindOrderReturnsNullWhenOrderReferenceIsInvalid(): void
    {
        // Arrange
        $companyUserTransfer = $this->tester->createCompanyUserWithPermission(static::PERMISSION_KEY_EDIT_COMPANY_ORDERS);

        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setIsAmendment(true)
            ->setOrderReference('INVALID-ORDER-REFERENCE')
            ->setCompanyUserTransfer($companyUserTransfer);

        // Act
        $orderTransfer = (new EditCompanyOrderCartReorderOrderProviderPlugin())->findOrder($cartReorderRequestTransfer);

        // Assert
        $this->assertNull($orderTransfer);
    }
}
