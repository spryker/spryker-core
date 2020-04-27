<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Sales
 * @group Business
 * @group Facade
 * @group SalesFacadeExpandItemsTest
 * Add your own group annotations below this line
 */
class SalesFacadeExpandItemsTest extends Test
{
    protected const ITEM_NAME = 'ITEM_NAME';
    protected const CURRENCY_ISO_CODE = 'CODE';

    /**
     * @var \SprykerTest\Zed\Sales\SalesBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testExpandItemsWithCurrencyIsoCodeWithOrderWithCurrencyCode(): void
    {
        // Arrange
        $salesFacade = $this->getSalesFacade();
        $itemTransfer = (new ItemBuilder([ItemTransfer::NAME => static::ITEM_NAME]))->build();
        $orderEntity = $this->tester->haveSalesOrderEntity([$itemTransfer], static::CURRENCY_ISO_CODE);
        $orderTransfer = $salesFacade->getCustomerOrderByOrderReference(
            (new OrderBuilder())->build()->fromArray([
                OrderTransfer::ORDER_REFERENCE => $orderEntity->getOrderReference(),
                OrderTransfer::CUSTOMER_REFERENCE => $orderEntity->getCustomerReference(),
            ])
        );

        // Act
        $itemTransfers = $salesFacade->expandItemsWithCurrencyIsoCode($orderTransfer->getItems()->getArrayCopy());

        // Assert
        $this->assertEquals($orderTransfer->getCurrencyIsoCode(), $itemTransfers[0]->getCurrencyIsoCode());
    }

    /**
     * @return void
     */
    public function testExpandItemsWithCurrencyIsoCodeWithOrderWithoutCurrencyCode(): void
    {
        // Arrange
        $salesFacade = $this->getSalesFacade();
        $itemTransfer = (new ItemBuilder([ItemTransfer::NAME => static::ITEM_NAME]))->build();
        $orderEntity = $this->tester->haveSalesOrderEntity([$itemTransfer]);
        $orderTransfer = $salesFacade->getCustomerOrderByOrderReference(
            (new OrderBuilder())->build()->fromArray([
                OrderTransfer::ORDER_REFERENCE => $orderEntity->getOrderReference(),
                OrderTransfer::CUSTOMER_REFERENCE => $orderEntity->getCustomerReference(),
            ])
        );

        // Act
        $itemTransfers = $salesFacade->expandItemsWithCurrencyIsoCode($orderTransfer->getItems()->getArrayCopy());

        // Assert
        $this->assertNull($itemTransfers[0]->getCurrencyIsoCode());
    }

    /**
     * @return void
     */
    public function testExpandItemsWithCurrencyIsoCodeWithoutOrder(): void
    {
        // Arrange
        $salesFacade = $this->getSalesFacade();
        $itemTransfer = (new ItemBuilder([ItemTransfer::NAME => static::ITEM_NAME]))->build();

        // Act
        $itemTransfers = $salesFacade->expandItemsWithCurrencyIsoCode([$itemTransfer]);

        // Assert
        $this->assertNull($itemTransfers[0]->getCurrencyIsoCode());
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    protected function getSalesFacade(): SalesFacadeInterface
    {
        return $this->tester->getLocator()->sales()->facade();
    }
}
