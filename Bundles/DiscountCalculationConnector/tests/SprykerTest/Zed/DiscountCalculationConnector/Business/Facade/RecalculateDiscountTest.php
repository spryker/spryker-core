<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DiscountCalculationConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DiscountCalculationConnector
 * @group Business
 * @group Facade
 * @group RecalculateDiscountTest
 * Add your own group annotations below this line
 */
class RecalculateDiscountTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const CURRENCY_CODE = 'fff';

    /**
     * @var int
     */
    protected const FAKE_ID_DISCOUNT = 12345;

    /**
     * @var \SprykerTest\Zed\DiscountCalculationConnector\DiscountCalculationConnectorBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected $currencyTransfer;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected $storeTransfer;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);
        $this->currencyTransfer = $this->tester->haveCurrencyTransfer([CurrencyTransfer::CODE => static::CURRENCY_CODE]);
    }

    /**
     * @return void
     */
    public function testRecalculateDiscountsRecalculatesChecksRequiredStoreProperty(): void
    {
        // Arrange
        $calculableObjectTransfer = (new CalculableObjectTransfer())
            ->setStore(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->recalculateDiscounts($calculableObjectTransfer);
    }

    /**
     * @return void
     */
    public function testRecalculateDiscountsRecalculatesWithAlreadyCalculatedDiscounts(): void
    {
        // Arrange
        $this->tester->createCartRuleDiscount($this->storeTransfer, $this->currencyTransfer);

        $fakeCalculatedDiscount = (new CalculatedDiscountTransfer())
            ->setIdDiscount(static::FAKE_ID_DISCOUNT);

        $calculableObjectTransfer = $this->createCalculableObjectTransfer($this->storeTransfer);
        $calculableObjectTransfer->getItems()->offsetGet(0)->addCalculatedDiscount($fakeCalculatedDiscount);

        // Act
        $calculableObjectTransfer = $this->tester->getFacade()->recalculateDiscounts($calculableObjectTransfer);

        // Assert
        $this->assertNotSame(
            static::FAKE_ID_DISCOUNT,
            $calculableObjectTransfer->getItems()->offsetGet(0)->getCalculatedDiscounts()->offsetGet(0)->getIdDiscount(),
        );
    }

    /**
     * @return void
     */
    public function testRecalculateDiscountsRecalculatesWithAlreadyCalculatedDiscountsAndRealDiscounts(): void
    {
        // Arrange
        $fakeCalculatedDiscount = (new CalculatedDiscountTransfer())
            ->setIdDiscount(static::FAKE_ID_DISCOUNT);

        $calculableObjectTransfer = $this->createCalculableObjectTransfer($this->storeTransfer);
        $calculableObjectTransfer->getItems()->offsetGet(0)->addCalculatedDiscount($fakeCalculatedDiscount);

        // Act
        $calculableObjectTransfer = $this->tester->getFacade()->recalculateDiscounts($calculableObjectTransfer);

        // Assert
        $this->assertEmpty($calculableObjectTransfer->getItems()->offsetGet(0)->getCalculatedDiscounts());
    }

    /**
     * @return void
     */
    public function testRecalculateDiscountsChecksOrderReferenceExpansion(): void
    {
        // Arrange
        $orderTransferMock = $this->getMockBuilder(OrderTransfer::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getOrderReference', 'toArray'])
            ->getMock();

        $orderTransferMock->method('toArray')->willReturn([]);

        $calculableObjectTransfer = $this->createCalculableObjectTransfer($this->storeTransfer);
        $calculableObjectTransfer->setOriginalOrder($orderTransferMock);

        // Assert
        $orderTransferMock
            ->expects($this->once())
            ->method('getOrderReference');

        // Act
        $this->tester->getFacade()->recalculateDiscounts($calculableObjectTransfer);
    }

    /**
     * @return void
     */
    public function testRecalculateDiscountsChecksOrderReferenceExpansionWithoutOrder(): void
    {
        // Arrange
        $orderTransferMock = $this->getMockBuilder(OrderTransfer::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getOrderReference', 'toArray'])
            ->getMock();

        $orderTransferMock->method('toArray')->willReturn([]);

        $calculableObjectTransfer = $this->createCalculableObjectTransfer($this->storeTransfer);

        // Assert
        $orderTransferMock
            ->expects($this->never())
            ->method('getOrderReference');

        // Act
        $this->tester->getFacade()->recalculateDiscounts($calculableObjectTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function createCalculableObjectTransfer(StoreTransfer $storeTransfer): CalculableObjectTransfer
    {
        $firstProductConcrete = $this->tester->haveProduct();
        $secondProductConcrete = $this->tester->haveProduct();

        $firstItem = (new ItemTransfer())
            ->setQuantity(2)
            ->setUnitPrice(1000)
            ->setSku($firstProductConcrete->getSku())
            ->setIdProductAbstract($firstProductConcrete->getFkProductAbstract());

        $secondItem = (new ItemTransfer())
            ->setQuantity(2)
            ->setUnitPrice(1000)
            ->setSku($secondProductConcrete->getSku())
            ->setIdProductAbstract($secondProductConcrete->getFkProductAbstract());

        return (new CalculableObjectTransfer())
            ->setStore($storeTransfer)
            ->setCurrency($this->currencyTransfer)
            ->addItem($firstItem)
            ->addItem($secondItem);
    }
}
