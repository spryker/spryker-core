<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleOrSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface;

class DecisionRuleOrSpecificationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return void
     */
    public function testIsSatisfiedWhenAnyReturnTrueShouldEvaluateToTrue()
    {
        $leftSpecificationMock = $this->createDecisionRuleSpecificationMock();
        $leftSpecificationMock->expects($this->once())
            ->method('isSatisfiedBy')
            ->willReturn(false);

        $rightSpecificationMock = $this->createDecisionRuleSpecificationMock();
        $rightSpecificationMock->expects($this->once())
            ->method('isSatisfiedBy')
            ->willReturn(true);

        $decisionRuleOrSpecification = $this->createDecisionRuleOrSpecification($leftSpecificationMock, $rightSpecificationMock);

        $isSatisfied = $decisionRuleOrSpecification->isSatisfiedBy(new QuoteTransfer(), new ItemTransfer());

        $this->assertTrue($isSatisfied);
    }

    /**
     * @return void
     */
    public function testIsSatisfiedWhenAllOfSpecificationReturnsFalseShouldEvaluateToFalse()
    {
        $leftSpecificationMock = $this->createDecisionRuleSpecificationMock();
        $leftSpecificationMock->expects($this->once())
            ->method('isSatisfiedBy')
            ->willReturn(false);

        $rightSpecificationMock = $this->createDecisionRuleSpecificationMock();
        $rightSpecificationMock->expects($this->once())
            ->method('isSatisfiedBy')
            ->willReturn(false);

        $decisionRuleAndSpecification = $this->createDecisionRuleOrSpecification($leftSpecificationMock, $rightSpecificationMock);

        $isSatisfied = $decisionRuleAndSpecification->isSatisfiedBy(new QuoteTransfer(), new ItemTransfer());

        $this->assertFalse($isSatisfied);
    }

    /**
     * @param $leftMock
     * @param $rightMock
     *
     * @return DecisionRuleOrSpecification
     */
    protected function createDecisionRuleOrSpecification($leftMock, $rightMock)
    {
        return new DecisionRuleOrSpecification($leftMock, $rightMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|DecisionRuleSpecificationInterface
     */
    protected function createDecisionRuleSpecificationMock()
    {
        return $this->getMock(DecisionRuleSpecificationInterface::class);
    }
}
