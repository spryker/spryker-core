<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleAndSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group Specification
 * @group DecisionRuleSpecification
 * @group DecisionRuleAndSpecificationTest
 */
class DecisionRuleAndSpecificationTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testIsSatisfiedWhenBothReturnTrueShouldEvaluateToTrue()
    {
        $leftSpecificationMock = $this->createDecisionRuleSpecificationMock();
        $leftSpecificationMock->expects($this->once())
            ->method('isSatisfiedBy')
            ->willReturn(true);

        $rightSpecificationMock = $this->createDecisionRuleSpecificationMock();
        $rightSpecificationMock->expects($this->once())
            ->method('isSatisfiedBy')
            ->willReturn(true);

        $decisionRuleAndSpecification = $this->createDecisionRuleAndSpecification($leftSpecificationMock, $rightSpecificationMock);

        $isSatisfied = $decisionRuleAndSpecification->isSatisfiedBy(new QuoteTransfer(), new ItemTransfer());

        $this->assertTrue($isSatisfied);
    }

    /**
     * @return void
     */
    public function testIsSatisfiedWhenAnyOfSpecificationReturnsFalseShouldEvaluateToFalse()
    {
        $leftSpecificationMock = $this->createDecisionRuleSpecificationMock();
        $leftSpecificationMock->expects($this->once())
            ->method('isSatisfiedBy')
            ->willReturn(true);

        $rightSpecificationMock = $this->createDecisionRuleSpecificationMock();
        $rightSpecificationMock->expects($this->once())
            ->method('isSatisfiedBy')
            ->willReturn(false);

        $decisionRuleAndSpecification = $this->createDecisionRuleAndSpecification($leftSpecificationMock, $rightSpecificationMock);

        $isSatisfied = $decisionRuleAndSpecification->isSatisfiedBy(new QuoteTransfer(), new ItemTransfer());

        $this->assertFalse($isSatisfied);
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface $leftMock
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface $rightMock
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleAndSpecification
     */
    protected function createDecisionRuleAndSpecification(DecisionRuleSpecificationInterface$leftMock, DecisionRuleSpecificationInterface $rightMock)
    {
        return new DecisionRuleAndSpecification($leftMock, $rightMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface
     */
    protected function createDecisionRuleSpecificationMock()
    {
        return $this->getMockBuilder(DecisionRuleSpecificationInterface::class)->getMock();
    }

}
