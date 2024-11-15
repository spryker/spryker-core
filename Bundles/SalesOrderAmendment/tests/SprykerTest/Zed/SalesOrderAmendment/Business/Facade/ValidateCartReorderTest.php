<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderAmendment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CartReorderBuilder;
use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesOrderAmendment
 * @group Business
 * @group Facade
 * @group ValidateCartReorderTest
 * Add your own group annotations below this line
 */
class ValidateCartReorderTest extends Unit
{
    /**
     * @uses \Spryker\Zed\SalesOrderAmendment\Business\Validator\CartReorderValidator::GLOSSARY_KEY_ORDER_REFERENCE_NOT_MATCH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_ORDER_REFERENCE_NOT_MATCH = 'sales_order_amendment.validation.cart_reorder.order_reference_not_match';

    /**
     * @var \SprykerTest\Zed\SalesOrderAmendment\SalesOrderAmendmentBusinessTester
     */
    protected SalesOrderAmendmentBusinessTester $tester;

    /**
     * @return void
     */
    public function testReturnsErrorWhenOrderReferencesAreNotEqual(): void
    {
        // Arrange
        $cartReorderTransfer = (new CartReorderBuilder())
            ->withQuote([QuoteTransfer::AMENDMENT_ORDER_REFERENCE => 'order-reference-1'])
            ->withOrder([OrderTransfer::ORDER_REFERENCE => 'order-reference-2'])
            ->build();

        // Act
        $cartReorderResponseTransfer = $this->tester->getFacade()->validateCartReorder(
            $cartReorderTransfer,
            new CartReorderResponseTransfer(),
        );

        // Assert
        $this->assertCount(1, $cartReorderResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_ORDER_REFERENCE_NOT_MATCH,
            $cartReorderResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testDoesNotReturnErrorWhenOrderReferencesAreEqual(): void
    {
        // Arrange
        $cartReorderTransfer = (new CartReorderBuilder())
            ->withQuote([QuoteTransfer::AMENDMENT_ORDER_REFERENCE => 'order-reference'])
            ->withOrder([OrderTransfer::ORDER_REFERENCE => 'order-reference'])
            ->build();

        // Act
        $cartReorderResponseTransfer = $this->tester->getFacade()->validateCartReorder(
            $cartReorderTransfer,
            new CartReorderResponseTransfer(),
        );

        // Assert
        $this->assertCount(0, $cartReorderResponseTransfer->getErrors());
    }
}
