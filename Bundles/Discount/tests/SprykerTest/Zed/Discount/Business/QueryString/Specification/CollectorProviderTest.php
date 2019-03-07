<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString\Specification;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorProvider;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorAndSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorContext;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorOrSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface;
use Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group Specification
 * @group CollectorProviderTest
 * Add your own group annotations below this line
 */
class CollectorProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testCreateAndShouldReturnInstanceOfAndCompositeSpecification()
    {
        $collectorProvider = $this->createCollectorProvider();
        $compositeSpecification = $collectorProvider->createAnd(
            $this->createCollectorSpecificationMock(),
            $this->createCollectorSpecificationMock()
        );

        $this->assertInstanceOf(CollectorAndSpecification::class, $compositeSpecification);
    }

    /**
     * @return void
     */
    public function testCreateOrShouldReturnInstanceOfOrCompositeSpecification()
    {
        $collectorProvider = $this->createCollectorProvider();
        $compositeSpecification = $collectorProvider->createOr(
            $this->createCollectorSpecificationMock(),
            $this->createCollectorSpecificationMock()
        );

        $this->assertInstanceOf(CollectorOrSpecification::class, $compositeSpecification);
    }

    /**
     * @return void
     */
    public function testGetSpecificationContextShouldReturnContextWithClauseAndPlugin()
    {
        $decisionRulePluginMock = $this->createCollectorPluginMock();
        $decisionRulePluginMock
            ->expects($this->once())
            ->method('getFieldName')
            ->willReturn('sku');

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setField('sku');

        $decisionRuleProvider = $this->createCollectorProvider($decisionRulePluginMock);
        $decisionRuleSpecificationContext = $decisionRuleProvider->getSpecificationContext($clauseTransfer);

        $this->assertInstanceOf(CollectorContext::class, $decisionRuleSpecificationContext);
    }

    /**
     * @return void
     */
    public function testGetSpecificationContextShouldThrowExceptionWhenSpecificationNotFound()
    {
        $this->expectException(QueryStringException::class);

        $decisionRulePluginMock = $this->createCollectorPluginMock();
        $decisionRulePluginMock
            ->expects($this->once())
            ->method('getFieldName')
            ->willReturn('does not exists');

        $clauseTransfer = new ClauseTransfer();
        $clauseTransfer->setField('sku');

        $decisionRuleProvider = $this->createCollectorProvider($decisionRulePluginMock);
        $decisionRuleProvider->getSpecificationContext($clauseTransfer);
    }

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface|null $collectorPluginMock
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorProvider
     */
    protected function createCollectorProvider(?CollectorPluginInterface $collectorPluginMock = null)
    {
        if ($collectorPluginMock === null) {
            $collectorPluginMock = $this->createCollectorPluginMock();
        }

        return new CollectorProvider([$collectorPluginMock]);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Dependency\Plugin\CollectorPluginInterface
     */
    protected function createCollectorPluginMock()
    {
        return $this->getMockBuilder(CollectorPluginInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface
     */
    protected function createCollectorSpecificationMock()
    {
        return $this->getMockBuilder(CollectorSpecificationInterface::class)->getMock();
    }
}
