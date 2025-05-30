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
use Spryker\Zed\CustomerDiscountConnector\Business\Checker\CustomerOrderCountDecisionRuleChecker;
use Spryker\Zed\CustomerDiscountConnector\Business\CustomerDiscountConnectorBusinessFactory;
use Spryker\Zed\CustomerDiscountConnector\Communication\Plugin\Discount\CustomerOrderAmountDecisionRulePlugin;
use Spryker\Zed\CustomerDiscountConnector\CustomerDiscountConnectorConfig;
use Spryker\Zed\CustomerDiscountConnector\Dependency\Facade\CustomerDiscountConnectorToDiscountFacadeInterface;
use Spryker\Zed\CustomerDiscountConnector\Persistence\CustomerDiscountConnectorRepositoryInterface;
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
 * @group CustomerOrderAmountDecisionRulePluginTest
 * Add your own group annotations below this line
 */
class CustomerOrderAmountDecisionRulePluginTest extends Unit
{
    /**
     * @var int
     */
    protected const TEST_CUSTOMER_ID = 1;

    /**
     * @var int
     */
    protected const TEST_DISCOUNT_ID = 100;

    /**
     * @var int
     */
    protected const TEST_ORDER_COUNT = 5;

    /**
     * @uses \Spryker\Zed\Discount\Business\QueryString\Comparator\LessThan::EXPRESSION
     *
     * @var string
     */
    protected const EXPRESSION_LESS_THAN = '<';

    /**
     * @var \SprykerTest\Zed\CustomerDiscountConnector\CustomerDiscountConnectorCommunicationTester
     */
    protected CustomerDiscountConnectorCommunicationTester $tester;

    /**
     * @return void
     */
    public function testReturnsFalseIfMetadataInClauseTransferIsEmpty(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setIdCustomer(static::TEST_CUSTOMER_ID)
                    ->setIsGuest(false),
            );

        $clauseTransfer = $this->tester->createClauseTransfer(
            static::EXPRESSION_LESS_THAN,
            static::TEST_ORDER_COUNT,
            [],
        );

        // Act
        $isCustomerOrderCountSatisfiedBy = (new CustomerOrderAmountDecisionRulePlugin())
            ->isSatisfiedBy(
                $quoteTransfer,
                new ItemTransfer(),
                $clauseTransfer,
            );

        // Assert
        $this->assertFalse($isCustomerOrderCountSatisfiedBy);
    }

    /**
     * @return void
     */
    public function testReturnsFalseIfCustomerFieldInQuoteIsEmpty(): void
    {
        // Arrange
        $quoteTransfer = new QuoteTransfer();

        $clauseTransfer = $this->tester->createClauseTransfer(
            static::EXPRESSION_LESS_THAN,
            static::TEST_ORDER_COUNT,
            ['id_discount' => static::TEST_DISCOUNT_ID],
        );

        // Act
        $isCustomerOrderCountSatisfiedBy = (new CustomerOrderAmountDecisionRulePlugin())
            ->isSatisfiedBy(
                $quoteTransfer,
                new ItemTransfer(),
                $clauseTransfer,
            );

        // Assert
        $this->assertFalse($isCustomerOrderCountSatisfiedBy);
    }

    /**
     * @return void
     */
    public function testReturnsFalseIfClauseTransferValueIsLessOrEqualThenAmountOfOrdersFromDiscount(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setIdCustomer(static::TEST_CUSTOMER_ID)
                    ->setIsGuest(false),
            );

        $clauseTransfer = $this->tester->createClauseTransfer(
            static::EXPRESSION_LESS_THAN,
            static::TEST_ORDER_COUNT,
            ['id_discount' => static::TEST_DISCOUNT_ID],
        );

        $customerOrderAmountDecisionRulePlugin = $this->createMockedPluginWithDependencies(
            static::TEST_CUSTOMER_ID,
            static::TEST_DISCOUNT_ID,
            static::TEST_ORDER_COUNT,
            false,
        );

        // Act
        $isCustomerOrderCountSatisfiedBy = $customerOrderAmountDecisionRulePlugin->isSatisfiedBy(
            $quoteTransfer,
            new ItemTransfer(),
            $clauseTransfer,
        );

        // Assert
        $this->assertFalse($isCustomerOrderCountSatisfiedBy);
    }

    /**
     * @return void
     */
    public function testReturnsTrueIfClauseTransferValueIsGreaterThenAmountOfOrdersFromDiscount(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer(
                (new CustomerTransfer())
                    ->setIdCustomer(static::TEST_CUSTOMER_ID)
                    ->setIsGuest(false),
            );

        $clauseTransfer = $this->tester->createClauseTransfer(
            static::EXPRESSION_LESS_THAN,
            static::TEST_ORDER_COUNT - 1,
            ['id_discount' => static::TEST_DISCOUNT_ID],
        );

        $customerOrderAmountDecisionRulePlugin = $this->createMockedPluginWithDependencies(
            static::TEST_CUSTOMER_ID,
            static::TEST_DISCOUNT_ID,
            static::TEST_ORDER_COUNT,
            true,
        );

        // Act
        $isCustomerOrderCountSatisfiedBy = $customerOrderAmountDecisionRulePlugin->isSatisfiedBy(
            $quoteTransfer,
            new ItemTransfer(),
            $clauseTransfer,
        );

        // Assert
        $this->assertTrue($isCustomerOrderCountSatisfiedBy);
    }

    /**
     * @param int $idCustomer The customer ID to expect in repository calls.
     * @param int $idDiscount The discount ID to expect in repository calls.
     * @param int $orderCount The order count to return from the repository.
     * @param bool $comparisonResult The result to return from the queryStringCompare method.
     *
     * @return \Spryker\Zed\CustomerDiscountConnector\Communication\Plugin\Discount\CustomerOrderAmountDecisionRulePlugin
     */
    protected function createMockedPluginWithDependencies(
        int $idCustomer,
        int $idDiscount,
        int $orderCount,
        bool $comparisonResult
    ): CustomerOrderAmountDecisionRulePlugin {
        $repositoryMock = $this->createMock(CustomerDiscountConnectorRepositoryInterface::class);
        $repositoryMock->expects($this->once())
            ->method('countCustomerDiscountUsages')
            ->with($idCustomer, $idDiscount)
            ->willReturn($orderCount);

        $discountFacadeMock = $this->createMock(CustomerDiscountConnectorToDiscountFacadeInterface::class);
        $discountFacadeMock->expects($this->once())
            ->method('queryStringCompare')
            ->with($this->anything(), $orderCount)
            ->willReturn($comparisonResult);

        $configMock = $this->createMock(CustomerDiscountConnectorConfig::class);

        $customerOrderCountDecisionRuleChecker = new CustomerOrderCountDecisionRuleChecker(
            $discountFacadeMock,
            $configMock,
            $repositoryMock,
        );

        $businessFactoryMock = $this->getMockBuilder(CustomerDiscountConnectorBusinessFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createCustomerOrderCountDecisionRuleChecker', 'getRepository', 'getDiscountFacade', 'getConfig'])
            ->getMock();

        $businessFactoryMock->expects($this->once())
            ->method('createCustomerOrderCountDecisionRuleChecker')
            ->willReturn($customerOrderCountDecisionRuleChecker);

        $customerOrderAmountDecisionRulePlugin = $this->createPartialMock(CustomerOrderAmountDecisionRulePlugin::class, ['getBusinessFactory']);
        $customerOrderAmountDecisionRulePlugin->expects($this->once())
            ->method('getBusinessFactory')
            ->willReturn($businessFactoryMock);

        return $customerOrderAmountDecisionRulePlugin;
    }
}
