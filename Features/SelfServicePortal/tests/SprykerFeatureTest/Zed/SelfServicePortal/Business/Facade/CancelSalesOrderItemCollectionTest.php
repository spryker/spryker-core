<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalDependencyProvider;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Business
 * @group Facade
 * @group CancelSalesOrderItemCollectionTest
 * Add your own group annotations below this line
 */
class CancelSalesOrderItemCollectionTest extends Unit
{
    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Business\Service\Canceler\OrderItemCanceler::GLOSSARY_KEY_VALIDATION_NO_ORDER_ITEMS_PROVIDED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_NO_ORDER_ITEMS_PROVIDED = 'self_service_portal.service.validation.no_order_items_provided';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Business\Service\Canceler\OrderItemCanceler::GLOSSARY_KEY_VALIDATION_STATUS_CHANGE_FAILED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_STATUS_CHANGE_FAILED = 'self_service_portal.service.validation.status_change_failed';

    /**
     * @var string
     */
    protected const CANCELLED_STATE_NAME = 'cancelled';

    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalBusinessTester
     */
    protected SelfServicePortalBusinessTester $tester;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    public function testCancelSalesOrderItemCollectionSuccessfullyCancelsOrderItem(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);

        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();
        $this->tester->setItemState($itemTransfer->getIdSalesOrderItem(), static::CANCELLED_STATE_NAME);

        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->addItem((new ItemTransfer())->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem()));

        // Act
        $salesOrderItemCollectionResponseTransfer = $this->tester->getFacade()->cancelSalesOrderItemCollection($salesOrderItemCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $salesOrderItemCollectionResponseTransfer->getErrors());
        $this->assertCount(1, $salesOrderItemCollectionResponseTransfer->getItems());

        $updatedSalesOrderItemEntity = $this->tester->findSalesOrderItemByIdSalesOrderItem($itemTransfer->getIdSalesOrderItem());
        $this->assertSame('cancelled', $updatedSalesOrderItemEntity->getState()->getName());
    }

    public function testCancelSalesOrderItemCollectionReturnsErrorWhenNoOrderItemsProvided(): void
    {
        // Arrange
        $salesOrderItemCollectionRequestTransfer = new SalesOrderItemCollectionRequestTransfer();

        // Act
        $salesOrderItemCollectionResponseTransfer = $this->tester->getFacade()->cancelSalesOrderItemCollection($salesOrderItemCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $salesOrderItemCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_VALIDATION_NO_ORDER_ITEMS_PROVIDED,
            $salesOrderItemCollectionResponseTransfer->getErrors()->getIterator()->current()->getMessage(),
        );
        $this->assertCount(0, $salesOrderItemCollectionResponseTransfer->getItems());
    }

    public function testCancelSalesOrderItemCollectionReturnsErrorWhenOmsFacadeReturnsNull(): void
    {
        // Arrange
        $orderTransfer = $this->tester->createOrderByStateMachineProcessName(static::DEFAULT_OMS_PROCESS_NAME);

        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $orderTransfer->getItems()->getIterator()->current();

        $salesOrderItemCollectionRequestTransfer = (new SalesOrderItemCollectionRequestTransfer())
            ->addItem((new ItemTransfer())->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem()));

        $omsFacadeMock = $this->getMockBuilder(OmsFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $omsFacadeMock->method('triggerEventForOrderItems')->willReturn(null);

        $this->tester->setDependency(SelfServicePortalDependencyProvider::FACADE_OMS, $omsFacadeMock);

        // Act
        $salesOrderItemCollectionResponseTransfer = $this->tester->getFacade()->cancelSalesOrderItemCollection($salesOrderItemCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $salesOrderItemCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_VALIDATION_STATUS_CHANGE_FAILED,
            $salesOrderItemCollectionResponseTransfer->getErrors()->getIterator()->current()->getMessage(),
        );
        $this->assertCount(1, $salesOrderItemCollectionResponseTransfer->getItems());
    }
}
