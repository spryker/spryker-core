<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantCommission\Communication\Specification\CollectorSpecification;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Spryker\Zed\MerchantCommission\Communication\Specification\CollectorRuleSpecification\CollectorRuleAndSpecification;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantCommission
 * @group Communication
 * @group Specification
 * @group CollectorSpecification
 * @group CollectorAndSpecificationTest
 * Add your own group annotations below this line
 */
class CollectorAndSpecificationTest extends Unit
{
    /**
     * @return void
     */
    public function testCollectShouldMergedUniqueDataFromBothCollections(): void
    {
        // Arrange
        $merchantCommissionCalculationRequestItemTransfers = [
            new MerchantCommissionCalculationRequestItemTransfer(),
        ];

        $leftNodeMock = $this->createCollectorRuleSpecificationMock($merchantCommissionCalculationRequestItemTransfers);

        $merchantCommissionCalculationRequestItemTransfers[] = new MerchantCommissionCalculationRequestItemTransfer();
        $rightNodeMock = $this->createCollectorRuleSpecificationMock($merchantCommissionCalculationRequestItemTransfers);

        // Act
        $collected = $this->createCollectorAndSpecification($leftNodeMock, $rightNodeMock)
            ->collect(new MerchantCommissionCalculationRequestTransfer());

        // Assert
        $this->assertCount(1, $collected);
    }

    /**
     * @return void
     */
    public function testCollectShouldMergedSameDataFromBothCollections(): void
    {
        // Arrange
        $merchantCommissionCalculationRequestItemTransfers = [
            new MerchantCommissionCalculationRequestItemTransfer(),
            new MerchantCommissionCalculationRequestItemTransfer(),
        ];
        $leftNodeMock = $this->createCollectorRuleSpecificationMock($merchantCommissionCalculationRequestItemTransfers);
        $rightNodeMock = $this->createCollectorRuleSpecificationMock($merchantCommissionCalculationRequestItemTransfers);

        // Act
        $collected = $this->createCollectorAndSpecification($leftNodeMock, $rightNodeMock)
            ->collect(new MerchantCommissionCalculationRequestTransfer());

        // Assert
        $this->assertCount(2, $collected);
    }

    /**
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface $leftNode
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface $rightNode
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface
     */
    protected function createCollectorAndSpecification(
        CollectorRuleSpecificationInterface $leftNode,
        CollectorRuleSpecificationInterface $rightNode
    ): CollectorRuleSpecificationInterface {
        return new CollectorRuleAndSpecification($leftNode, $rightNode);
    }

    /**
     * @param list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer> $merchantCommissionCalculationRequestItemTransfers
     *
     * @return \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface
     */
    protected function createCollectorRuleSpecificationMock(
        array $merchantCommissionCalculationRequestItemTransfers
    ): CollectorRuleSpecificationInterface {
        $collectorRuleSpecificationMock = $this->getMockBuilder(CollectorRuleSpecificationInterface::class)->getMock();
        $collectorRuleSpecificationMock->expects($this->once())
            ->method('collect')
            ->willReturn($merchantCommissionCalculationRequestItemTransfers);

        return $collectorRuleSpecificationMock;
    }
}
