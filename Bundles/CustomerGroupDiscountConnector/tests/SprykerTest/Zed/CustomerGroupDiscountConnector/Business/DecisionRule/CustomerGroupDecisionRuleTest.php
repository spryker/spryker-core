<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerGroupDiscountConnector\Business\DecisionRule;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CustomerGroupDiscountConnector\Business\DecisionRule\CustomerGroupDecisionRule;
use Spryker\Zed\CustomerGroupDiscountConnector\Dependency\Facade\CustomerGroupDiscountConnectorToCustomerGroupFacadeInterface;
use Spryker\Zed\CustomerGroupDiscountConnector\Dependency\Facade\CustomerGroupDiscountConnectorToDiscountFacadeInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CustomerGroupDiscountConnector
 * @group Business
 * @group DecisionRule
 * @group CustomerGroupDecisionRuleTest
 * Add your own group annotations below this line
 */
class CustomerGroupDecisionRuleTest extends Unit
{
    /**
     * @return void
     */
    public function testIsSatisfiedWhenCustomerIsNotSetShouldReturnFalse()
    {
        $customerGroupDecisionRule = $this->createCustomerGroupDecisionRule();

        $this->assertFalse(
            $customerGroupDecisionRule->isSatisfiedBy($this->createQuoteTransfer(), $this->createItemTransfer(), $this->createClauseTransfer())
        );
    }

    /**
     * @return void
     */
    public function testIsSatisfiedWhenCustomerGroupIsNotSetShouldReturnFalse()
    {
        $customerGroupDecisionRule = $this->createCustomerGroupDecisionRule();

        $quoteTransfer = $this->createQuoteTransfer();

        $customerTransfer = $this->createCustomerTransfer();
        $customerTransfer->setIdCustomer(1);
        $quoteTransfer->setCustomer($customerTransfer);

        $this->assertFalse(
            $customerGroupDecisionRule->isSatisfiedBy($quoteTransfer, $this->createItemTransfer(), $this->createClauseTransfer())
        );
    }

    /**
     * @return void
     */
    public function testIsSatisfiedWhenAllDataIsPresentShouldExecuteDiscountQueryString()
    {
        $discountFacadeMock = $this->createDiscountFacadeMock();
        $customerGroupFacadeMock = $this->createCustomerGroupFacadeMock();

        $quoteTransfer = $this->createQuoteTransfer();
        $customerTransfer = $this->createCustomerTransfer();
        $customerTransfer->setIdCustomer(1);
        $quoteTransfer->setCustomer($customerTransfer);

        $clauseTransfer = $this->createClauseTransfer();

        $customerGroupTransfer = $this->createCustomerGroupTransfer();
        $customerGroupTransfer->setName('test');

        $discountFacadeMock
            ->expects($this->once())
            ->method('queryStringCompare')
            ->with($clauseTransfer, $customerGroupTransfer->getName())
            ->willReturn(true);

        $customerGroupFacadeMock->expects($this->once())
            ->method('findCustomerGroupByIdCustomer')
            ->willReturn($customerGroupTransfer);

        $customerGroupDecisionRule = $this->createCustomerGroupDecisionRule($discountFacadeMock, $customerGroupFacadeMock);
        $customerGroupDecisionRule->isSatisfiedBy($quoteTransfer, $this->createItemTransfer(), $clauseTransfer);
    }

    /**
     * @param \Spryker\Zed\CustomerGroupDiscountConnector\Dependency\Facade\CustomerGroupDiscountConnectorToDiscountFacadeInterface|null $discountFacadeMock
     * @param \Spryker\Zed\CustomerGroupDiscountConnector\Dependency\Facade\CustomerGroupDiscountConnectorToCustomerGroupFacadeInterface|null $customerGroupFacadeMock
     *
     * @return \Spryker\Zed\CustomerGroupDiscountConnector\Business\DecisionRule\CustomerGroupDecisionRule
     */
    protected function createCustomerGroupDecisionRule(
        ?CustomerGroupDiscountConnectorToDiscountFacadeInterface $discountFacadeMock = null,
        ?CustomerGroupDiscountConnectorToCustomerGroupFacadeInterface $customerGroupFacadeMock = null
    ) {

        if ($discountFacadeMock === null) {
            $discountFacadeMock = $this->createDiscountFacadeMock();
        }

        if ($customerGroupFacadeMock === null) {
            $customerGroupFacadeMock = $this->createCustomerGroupFacadeMock();
        }

        return new CustomerGroupDecisionRule($discountFacadeMock, $customerGroupFacadeMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\CustomerGroupDiscountConnector\Dependency\Facade\CustomerGroupDiscountConnectorToDiscountFacadeInterface
     */
    protected function createDiscountFacadeMock()
    {
        return $this->getMockBuilder(CustomerGroupDiscountConnectorToDiscountFacadeInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\CustomerGroupDiscountConnector\Dependency\Facade\CustomerGroupDiscountConnectorToCustomerGroupFacadeInterface
     */
    protected function createCustomerGroupFacadeMock()
    {
        return $this->getMockBuilder(CustomerGroupDiscountConnectorToCustomerGroupFacadeInterface::class)->getMock();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer()
    {
        return new QuoteTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\ClauseTransfer
     */
    protected function createClauseTransfer()
    {
        return new ClauseTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer()
    {
        return new ItemTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer()
    {
        return new CustomerTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer
     */
    protected function createCustomerGroupTransfer()
    {
        return new CustomerGroupTransfer();
    }
}
