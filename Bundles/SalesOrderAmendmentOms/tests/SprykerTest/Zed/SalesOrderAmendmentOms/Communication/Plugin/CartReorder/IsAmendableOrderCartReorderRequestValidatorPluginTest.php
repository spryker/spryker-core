<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendmentOms\Communication\Plugin\CartReorder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\CartReorder\IsAmendableOrderCartReorderRequestValidatorPlugin;
use SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendmentOms
 * @group Communication
 * @group Plugin
 * @group CartReorder
 * @group IsAmendableOrderCartReorderRequestValidatorPluginTest
 * Add your own group annotations below this line
 */
class IsAmendableOrderCartReorderRequestValidatorPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const ORDER_ITEM_STATE_PAYMENT_PENDING = 'payment pending';

    /**
     * @var string
     */
    protected const ORDER_ITEM_STATE_PAID = 'paid';

    /**
     * @uses \Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\QuoteValidator::GLOSSARY_KEY_VALIDATION_ORDER_NOT_AMENDABLE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ORDER_NOT_AMENDABLE = 'sales_order_amendment_oms.validation.order_not_amendable';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsCommunicationTester
     */
    protected SalesOrderAmendmentOmsCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureOrderAmendmentTestStateMachine();
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenIsAmendmentIsNotSet(): void
    {
        // Act
        $cartReorderResponseTransfer = (new IsAmendableOrderCartReorderRequestValidatorPlugin())
            ->validate(new CartReorderRequestTransfer(), new CartReorderResponseTransfer());

        // Assert
        $this->assertEmpty($cartReorderResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenIsAmendmentIsSetToFalse(): void
    {
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setIsAmendment(false);

        // Act
        $cartReorderResponseTransfer = (new IsAmendableOrderCartReorderRequestValidatorPlugin())
            ->validate($cartReorderRequestTransfer, new CartReorderResponseTransfer());

        // Assert
        $this->assertEmpty($cartReorderResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShouldThrowNullValueExceptionWhenOrderReferenceIsNotSet(): void
    {
        // Arrange
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setIsAmendment(true);

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "orderReference" of transfer `Generated\Shared\Transfer\CartReorderRequestTransfer` is null.');

        // Act
        (new IsAmendableOrderCartReorderRequestValidatorPlugin())
            ->validate($cartReorderRequestTransfer, new CartReorderResponseTransfer());
    }

    /**
     * @return void
     */
    public function testShouldReturnEmptyErrorCollectionWhenProvidedOrderIsAmendable(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrderWithTwoItems();
        $this->tester->setItemState($saveOrderTransfer->getOrderItems()->offsetGet(0)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_PAYMENT_PENDING);
        $this->tester->setItemState($saveOrderTransfer->getOrderItems()->offsetGet(1)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_PAYMENT_PENDING);
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setIsAmendment(true)
            ->setOrderReference($saveOrderTransfer->getOrderReferenceOrFail());

        // Act
        $cartReorderResponseTransfer = (new IsAmendableOrderCartReorderRequestValidatorPlugin())->validate(
            $cartReorderRequestTransfer,
            new CartReorderResponseTransfer(),
        );

        // Assert
        $this->assertEmpty($cartReorderResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShouldReturnErrorWhenProvidedOrderIsNotAmendable(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrderWithTwoItems();
        $this->tester->setItemState($saveOrderTransfer->getOrderItems()->offsetGet(0)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_PAYMENT_PENDING);
        $this->tester->setItemState($saveOrderTransfer->getOrderItems()->offsetGet(1)->getIdSalesOrderItemOrFail(), static::ORDER_ITEM_STATE_PAID);
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())
            ->setIsAmendment(true)
            ->setOrderReference($saveOrderTransfer->getOrderReferenceOrFail());

        // Act
        $cartReorderResponseTransfer = (new IsAmendableOrderCartReorderRequestValidatorPlugin())->validate(
            $cartReorderRequestTransfer,
            new CartReorderResponseTransfer(),
        );

        // Assert
        $this->assertCount(1, $cartReorderResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_VALIDATION_ORDER_NOT_AMENDABLE,
            $cartReorderResponseTransfer->getErrors()->getIterator()->current()->getMessage(),
        );
    }
}
