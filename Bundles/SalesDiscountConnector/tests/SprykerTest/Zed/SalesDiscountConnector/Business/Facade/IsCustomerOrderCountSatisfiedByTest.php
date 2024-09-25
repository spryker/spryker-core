<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesDiscountConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CurrencyBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerTest\Zed\SalesDiscountConnector\SalesDiscountConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesDiscountConnector
 * @group Business
 * @group Facade
 * @group IsCustomerOrderCountSatisfiedByTest
 * Add your own group annotations below this line
 */
class IsCustomerOrderCountSatisfiedByTest extends Unit
{
    /**
     * @use \Spryker\Zed\Discount\Business\QueryString\Comparator\Less::EXPRESSION
     *
     * @var string
     */
    protected const LESS_EXPRESSION = '<';

    /**
     * @use \Spryker\Zed\Discount\Business\QueryString\Comparator\Greater::EXPRESSION
     *
     * @var string
     */
    protected const GREATER_EXPRESSION = '>';

    /**
     * @use \Spryker\Zed\Discount\Business\QueryString\Comparator\Equal::EXPRESSION
     *
     * @var string
     */
    protected const EQUAL_EXPRESSION = '=';

    /**
     * @var string
     */
    public const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var string
     */
    protected const CURRENCY_ISO_CODE = 'CODE';

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @var \SprykerTest\Zed\SalesDiscountConnector\SalesDiscountConnectorBusinessTester
     */
    protected SalesDiscountConnectorBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldBeSatisfiedByCustomerOrderCountWhenOrderCountLessThanOne(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setCustomer($this->tester->haveCustomer());

        // Act
        $isCustomerOrderCountSatisfied = $this->tester
            ->getFacade()
            ->isCustomerOrderCountSatisfiedBy(
                $quoteTransfer,
                $this->tester->createClauseTransfer(static::LESS_EXPRESSION, '1'),
            );

        // Assert
        $this->assertTrue($isCustomerOrderCountSatisfied);
    }

    /**
     * @return void
     */
    public function testShouldNotBeBeSatisfiedByCustomerOrderCountGreaterThanOne(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setCustomer($this->tester->haveCustomer());

        // Act
        $isCustomerOrderCountSatisfied = $this->tester
            ->getFacade()
            ->isCustomerOrderCountSatisfiedBy(
                $quoteTransfer,
                $this->tester->createClauseTransfer(static::GREATER_EXPRESSION, '1'),
            );

        // Assert
        $this->assertFalse($isCustomerOrderCountSatisfied);
    }

    /**
     * @return void
     */
    public function testShouldBeSatisfiedByCustomerOrderCountEqualOne(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $quoteTransfer = $this->tester->buildQuote([
            QuoteTransfer::CURRENCY => (new CurrencyBuilder([CurrencyTransfer::CODE => static::CURRENCY_ISO_CODE]))->build(),
            QuoteTransfer::CUSTOMER => $customerTransfer,
            QuoteTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference(),
        ]);

        $this->tester->haveOrderFromQuote($quoteTransfer, static::DEFAULT_OMS_PROCESS_NAME);

        // Act
        $isCustomerOrderCountSatisfied = $this->tester
            ->getFacade()
            ->isCustomerOrderCountSatisfiedBy(
                $quoteTransfer,
                $this->tester->createClauseTransfer(static::EQUAL_EXPRESSION, '1'),
            );

        // Assert
        $this->assertTrue($isCustomerOrderCountSatisfied);
    }

    /**
     * @return void
     */
    public function testShouldNotBeSatisfiedByCustomerOrderCountWithoutCustomer(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setCustomer(null);

        // Act
        $isCustomerOrderCountSatisfied = $this->tester
            ->getFacade()
            ->isCustomerOrderCountSatisfiedBy(
                $quoteTransfer,
                $this->tester->createClauseTransfer(static::EQUAL_EXPRESSION, '1'),
            );

        // Assert
        $this->assertFalse($isCustomerOrderCountSatisfied);
    }

    /**
     * @return void
     */
    public function testShouldNotBeSatisfiedByCustomerOrderCountWithoutIdCustomer(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setCustomer((new CustomerTransfer())->setIdCustomer(null));

        // Act
        $isCustomerOrderCountSatisfied = $this->tester
            ->getFacade()
            ->isCustomerOrderCountSatisfiedBy(
                $quoteTransfer,
                $this->tester->createClauseTransfer(static::EQUAL_EXPRESSION, '1'),
            );

        // Assert
        $this->assertFalse($isCustomerOrderCountSatisfied);
    }
}
