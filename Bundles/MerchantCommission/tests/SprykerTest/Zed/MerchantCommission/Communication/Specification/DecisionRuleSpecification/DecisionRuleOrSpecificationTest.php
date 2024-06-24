<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommission\Communication\Specification\DecisionRuleSpecification;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Spryker\Zed\MerchantCommission\Communication\Specification\DecisionRuleSpecification\DecisionRuleOrSpecification;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCommission
 * @group Communication
 * @group Specification
 * @group DecisionRuleSpecification
 * @group DecisionRuleOrSpecificationTest
 * Add your own group annotations below this line
 */
class DecisionRuleOrSpecificationTest extends Unit
{
    /**
     * @return void
     */
    public function testIsSatisfiedReturnsTrueWhenAtLeastOneNodeReturnsTrue(): void
    {
        // Arrange
        $leftNodeMock = $this->createDecisionRuleSpecificationMock(false);
        $rightNodeMock = $this->createDecisionRuleSpecificationMock(true);

        // Act
        $isSatisfied = $this->createDecisionOrSpecification($leftNodeMock, $rightNodeMock)
            ->isSatisfiedBy(new MerchantCommissionCalculationRequestTransfer());

        // Assert
        $this->assertTrue($isSatisfied);
    }

    /**
     * @return void
     */
    public function testIsSatisfiedReturnsFalseWhenAllOfNodesReturnsFalse(): void
    {
        // Arrange
        $leftNodeMock = $this->createDecisionRuleSpecificationMock(false);
        $rightNodeMock = $this->createDecisionRuleSpecificationMock(false);

        // Act
        $isSatisfied = $this->createDecisionOrSpecification($leftNodeMock, $rightNodeMock)
            ->isSatisfiedBy(new MerchantCommissionCalculationRequestTransfer());

        // Assert
        $this->assertFalse($isSatisfied);
    }

    /**
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface $leftNode
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface $rightNode
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface
     */
    protected function createDecisionOrSpecification(
        DecisionRuleSpecificationInterface $leftNode,
        DecisionRuleSpecificationInterface $rightNode
    ): DecisionRuleSpecificationInterface {
        return new DecisionRuleOrSpecification($leftNode, $rightNode);
    }

    /**
     * @param bool $isSatisfiedBy
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface
     */
    protected function createDecisionRuleSpecificationMock(bool $isSatisfiedBy): DecisionRuleSpecificationInterface
    {
        $decisionRuleSpecificationMock = $this->getMockBuilder(DecisionRuleSpecificationInterface::class)->getMock();
        $decisionRuleSpecificationMock->method('isSatisfiedBy')->willReturn($isSatisfiedBy);

        return $decisionRuleSpecificationMock;
    }
}
