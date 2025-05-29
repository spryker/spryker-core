<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerDiscountConnector\Communication\Plugin\Discount;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CustomerDiscountConnector\Communication\Plugin\Discount\CustomerReferenceDecisionRulePlugin;
use SprykerTest\Zed\CustomerDiscountConnector\CustomerDiscountConnectorCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CustomerDiscountConnector
 * @group Communication
 * @group Plugin
 * @group Discount
 * @group CustomerReferenceDecisionRulePluginTest
 * Add your own group annotations below this line
 */
class CustomerReferenceDecisionRulePluginTest extends Unit
{
 /**
  * @var string
  */
    protected const TEST_CUSTOMER_REFERENCE = 'test_customer_reference';

    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\Comparator\Equal::EXPRESSION
     *
     * @var string
     */
    protected const EXPRESSION_EQUAL = '=';

    /**
     * @var \SprykerTest\Zed\CustomerDiscountConnector\CustomerDiscountConnectorCommunicationTester
     */
    protected CustomerDiscountConnectorCommunicationTester $tester;

    /**
     * @return void
     */
    public function testReturnsTrueWhenCustomerReferenceMatchesTheClause(): void
    {
        // Act
        $isCustomerReferenceSatisfiedBy = (new CustomerReferenceDecisionRulePlugin())
            ->isSatisfiedBy(
                (new QuoteTransfer())->setCustomer((new CustomerTransfer())->setCustomerReference(static::TEST_CUSTOMER_REFERENCE)),
                new ItemTransfer(),
                $this->tester->createClauseTransfer(static::EXPRESSION_EQUAL, static::TEST_CUSTOMER_REFERENCE),
            );

        // Assert
        $this->assertTrue($isCustomerReferenceSatisfiedBy);
    }

    /**
     * @return void
     */
    public function testReturnsFalseWhenCustomerReferenceDoesNotMatchTheClause(): void
    {
        // Act
        $isCustomerReferenceSatisfiedBy = (new CustomerReferenceDecisionRulePlugin())
            ->isSatisfiedBy(
                (new QuoteTransfer())->setCustomer((new CustomerTransfer())->setCustomerReference('another_customer_reference')),
                new ItemTransfer(),
                $this->tester->createClauseTransfer(static::EXPRESSION_EQUAL, static::TEST_CUSTOMER_REFERENCE),
            );

        // Assert
        $this->assertFalse($isCustomerReferenceSatisfiedBy);
    }

    /**
     * @return void
     */
    public function testReturnsFalseWhenCustomerIsNotSetToQuote(): void
    {
        // Act
        $isCustomerReferenceSatisfiedBy = (new CustomerReferenceDecisionRulePlugin())
            ->isSatisfiedBy(
                new QuoteTransfer(),
                new ItemTransfer(),
                $this->tester->createClauseTransfer(static::EXPRESSION_EQUAL, static::TEST_CUSTOMER_REFERENCE),
            );

        // Assert
        $this->assertFalse($isCustomerReferenceSatisfiedBy);
    }

    /**
     * @return void
     */
    public function testReturnsFalseWhenCustomerReferenceIsNotSetToQuote(): void
    {
        // Act
        $isCustomerReferenceSatisfiedBy = (new CustomerReferenceDecisionRulePlugin())
            ->isSatisfiedBy(
                (new QuoteTransfer())->setCustomer(new CustomerTransfer()),
                new ItemTransfer(),
                $this->tester->createClauseTransfer(static::EXPRESSION_EQUAL, static::TEST_CUSTOMER_REFERENCE),
            );

        // Assert
        $this->assertFalse($isCustomerReferenceSatisfiedBy);
    }
}
