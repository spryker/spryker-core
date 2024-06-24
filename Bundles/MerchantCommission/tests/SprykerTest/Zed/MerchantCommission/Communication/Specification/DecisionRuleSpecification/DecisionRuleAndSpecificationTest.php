<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommission\Communication\Specification\DecisionRuleSpecification;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Spryker\Zed\MerchantCommission\Communication\Specification\DecisionRuleSpecification\DecisionRuleAndSpecification;
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
 * @group DecisionRuleAndSpecificationTest
 * Add your own group annotations below this line
 */
class DecisionRuleAndSpecificationTest extends Unit
{
    /**
     * @return void
     */
    public function testIsSatisfiedReturnsTrueWhenAllNodeReturnsTrue(): void
    {
        // Arrange
        $leftNodeMock = $this->createDecisionRuleSpecificationMock(true);
        $rightNodeMock = $this->createDecisionRuleSpecificationMock(true);

        // Act
        $isSatisfied = $this->createDecisionAndSpecification($leftNodeMock, $rightNodeMock)
            ->isSatisfiedBy(new MerchantCommissionCalculationRequestTransfer());

        // Assert
        $this->assertTrue($isSatisfied);
    }

    /**
     * @return void
     */
    public function testIsSatisfiedReturnsFalseWhenAtLeastOneNodeReturnsFalse(): void
    {
        // Arrange
        $leftNodeMock = $this->createDecisionRuleSpecificationMock(false);
        $rightNodeMock = $this->createDecisionRuleSpecificationMock(true);

        // Act
        $isSatisfied = $this->createDecisionAndSpecification($leftNodeMock, $rightNodeMock)
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
    protected function createDecisionAndSpecification(
        DecisionRuleSpecificationInterface $leftNode,
        DecisionRuleSpecificationInterface $rightNode
    ): DecisionRuleSpecificationInterface {
        return new DecisionRuleAndSpecification($leftNode, $rightNode);
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
