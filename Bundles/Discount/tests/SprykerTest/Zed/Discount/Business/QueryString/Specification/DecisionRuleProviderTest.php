<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString\Specification;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleProvider;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleAndSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleContext;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleOrSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group Specification
 * @group DecisionRuleProviderTest
 * Add your own group annotations below this line
 */
class DecisionRuleProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testCreateAndShouldReturnInstanceOfAndCompositeSpecification()
    {
        $decisionRuleProvider = $this->createDecisionRuleProvider();
        $compositeSpecification = $decisionRuleProvider->createAnd(
            $this->createDecisionRuleSpecificationMock(),
            $this->createDecisionRuleSpecificationMock()
        );

        $this->assertInstanceOf(DecisionRuleAndSpecification::class, $compositeSpecification);
    }

    /**
     * @return void
     */
    public function testCreateOrShouldReturnInstanceOfOrCompositeSpecification()
    {
        $decisionRuleProvider = $this->createDecisionRuleProvider();
        $compositeSpecification = $decisionRuleProvider->createOr(
            $this->createDecisionRuleSpecificationMock(),
            $this->createDecisionRuleSpecificationMock()
        );

        $this->assertInstanceOf(DecisionRuleOrSpecification::class, $compositeSpecification);
    }

    /**
     * @return void
     */
    public function testGetSpecificationContextShouldReturnContextWithClauseAndPlugin()
    {
        $decisionRulePluginMock = $this->createDecisionRulePluginMock();
        $decisionRulePluginMock
            ->expects($this->once())
            ->method('getFieldName')
            ->willReturn('sku');

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setField('sku');

        $decisionRuleProvider = $this->createDecisionRuleProvider($decisionRulePluginMock);
        $decisionRuleSpecificationContext = $decisionRuleProvider->getSpecificationContext($clauseTransfer);

        $this->assertInstanceOf(DecisionRuleContext::class, $decisionRuleSpecificationContext);
    }

    /**
     * @return void
     */
    public function testGetSpecificationContextShouldThrowExceptionWhenSpecificationNotFound()
    {
        $this->expectException(QueryStringException::class);

        $decisionRulePluginMock = $this->createDecisionRulePluginMock();
        $decisionRulePluginMock
            ->expects($this->once())
            ->method('getFieldName')
            ->willReturn('does not exists');

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setField('sku');

        $decisionRuleProvider = $this->createDecisionRuleProvider($decisionRulePluginMock);
        $decisionRuleProvider->getSpecificationContext($clauseTransfer);
    }

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface|null $decisionRulePluginMock
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleProvider
     */
    protected function createDecisionRuleProvider($decisionRulePluginMock = null)
    {
        if ($decisionRulePluginMock === null) {
            $decisionRulePluginMock = $this->createDecisionRulePluginMock();
        }

        return new DecisionRuleProvider([$decisionRulePluginMock]);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface
     */
    protected function createDecisionRulePluginMock()
    {
        return $this->getMockBuilder(DecisionRulePluginInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface
     */
    protected function createDecisionRuleSpecificationMock()
    {
        return $this->getMockBuilder(DecisionRuleSpecificationInterface::class)->getMock();
    }
}
