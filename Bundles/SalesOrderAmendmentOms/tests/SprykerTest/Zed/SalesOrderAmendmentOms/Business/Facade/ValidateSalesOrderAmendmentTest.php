<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendmentOms\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendmentOms
 * @group Business
 * @group Facade
 * @group ValidateSalesOrderAmendmentTest
 * Add your own group annotations below this line
 */
class ValidateSalesOrderAmendmentTest extends Unit
{
    /**
     * @uses \Spryker\Zed\SalesOrderAmendmentOms\Business\Validator\SalesOrderAmendmentValidator::GLOSSARY_KEY_VALIDATION_ORDER_DOES_NOT_EXIST
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ORDER_DOES_NOT_EXIST = 'sales_order_amendment_oms.validation.order_does_not_exist';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsBusinessTester
     */
    protected SalesOrderAmendmentOmsBusinessTester $tester;

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
    public function testShouldReturnValidationErrorWhenOriginalOrderDoesNotExist(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrderWithTwoItems();
        $salesOrderAmendmentTransfer = (new SalesOrderAmendmentTransfer())
            ->setOriginalOrderReference('non-existing-order-reference')
            ->setAmendedOrderReference($saveOrderTransfer->getOrderReferenceOrFail());

        // Act
        $errorCollectionTransfer = $this->tester->getFacade()
            ->validateSalesOrderAmendment($salesOrderAmendmentTransfer);

        // Assert
        $this->assertSame(
            static::GLOSSARY_KEY_VALIDATION_ORDER_DOES_NOT_EXIST,
            $errorCollectionTransfer->getErrors()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnValidationErrorWhenAmendedOrderDoesNotExist(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrderWithTwoItems();
        $salesOrderAmendmentTransfer = (new SalesOrderAmendmentTransfer())
            ->setOriginalOrderReference($saveOrderTransfer->getOrderReferenceOrFail())
            ->setAmendedOrderReference('non-existing-order-reference');

        // Act
        $errorCollectionTransfer = $this->tester->getFacade()
            ->validateSalesOrderAmendment($salesOrderAmendmentTransfer);

        // Assert
        $this->assertSame(
            static::GLOSSARY_KEY_VALIDATION_ORDER_DOES_NOT_EXIST,
            $errorCollectionTransfer->getErrors()->getIterator()->current()->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenOriginalOrderReferenceIsNotProvided(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrderWithTwoItems();
        $salesOrderAmendmentTransfer = (new SalesOrderAmendmentTransfer())
            ->setOriginalOrderReference(null)
            ->setAmendedOrderReference($saveOrderTransfer->getOrderReferenceOrFail());

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage(sprintf('Property "originalOrderReference" of transfer `%s` is null.', SalesOrderAmendmentTransfer::class));

        // Act
        $this->tester->getFacade()->validateSalesOrderAmendment($salesOrderAmendmentTransfer);
    }

    /**
     * @return void
     */
    public function testShouldThrowExceptionWhenAmendedOrderReferenceIsNotProvided(): void
    {
        // Arrange
        $saveOrderTransfer = $this->tester->haveOrderWithTwoItems();
        $salesOrderAmendmentTransfer = (new SalesOrderAmendmentTransfer())
            ->setOriginalOrderReference($saveOrderTransfer->getOrderReferenceOrFail())
            ->setAmendedOrderReference(null);

        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage(sprintf('Property "amendedOrderReference" of transfer `%s` is null.', SalesOrderAmendmentTransfer::class));

        // Act
        $this->tester->getFacade()->validateSalesOrderAmendment($salesOrderAmendmentTransfer);
    }
}
