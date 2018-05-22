<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString\Specification;

use Codeception\Test\Unit;
use Spryker\Zed\Discount\Business\DiscountBusinessFactory;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Business\QueryString\LogicalComparators;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProvider;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaProviderFactory;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group Specification
 * @group MetaProviderFactoryTest
 * Add your own group annotations below this line
 */
class MetaProviderFactoryTest extends Unit
{
    /**
     * @return void
     */
    public function testCreateMetaProviderByTypeForDecisionRuleShouldReturnMetaProviderForDecisionRule()
    {
        $discountBusinessFactoryMock = $this->createDiscountBusinessFactoryMock();
        $discountBusinessFactoryMock->expects($this->once())
            ->method('getDecisionRulePlugins')
            ->willReturn([]);

        $discountBusinessFactoryMock->expects($this->once())
            ->method('createComparatorOperators')
            ->willReturn($this->createComparatorOperatorsMock());

        $discountBusinessFactoryMock->expects($this->once())
            ->method('createLogicalComparators')
            ->willReturn($this->createLogicalComparatorsMock());

        $metaProviderFactoryMock = $this->createMetaProviderFactory($discountBusinessFactoryMock);

        $decisionRuleProvider = $metaProviderFactoryMock->createMetaProviderByType(
            MetaProviderFactory::TYPE_DECISION_RULE
        );

        $this->assertInstanceOf(MetaDataProvider::class, $decisionRuleProvider);
    }

    /**
     * @return void
     */
    public function testCreateMetaProviderByTypeForCollectorShouldReturnMetaProviderForCollector()
    {
        $discountBusinessFactoryMock = $this->createDiscountBusinessFactoryMock();
        $discountBusinessFactoryMock->expects($this->once())
            ->method('getCollectorPlugins')
            ->willReturn([]);

        $discountBusinessFactoryMock->expects($this->once())
            ->method('createComparatorOperators')
            ->willReturn($this->createComparatorOperatorsMock());

        $discountBusinessFactoryMock->expects($this->once())
            ->method('createLogicalComparators')
            ->willReturn($this->createLogicalComparatorsMock());

        $metaProviderFactoryMock = $this->createMetaProviderFactory($discountBusinessFactoryMock);

        $collectorProvider = $metaProviderFactoryMock->createMetaProviderByType(
            MetaProviderFactory::TYPE_COLLECTOR
        );

        $this->assertInstanceOf(MetaDataProvider::class, $collectorProvider);
    }

    /**
     * @param \Spryker\Zed\Discount\Business\DiscountBusinessFactory|null $discountBusinessFactoryMock
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\MetaProviderFactory
     */
    protected function createMetaProviderFactory(?DiscountBusinessFactory $discountBusinessFactoryMock = null)
    {
        if (!isset($discountBusinessFactoryMock)) {
            $discountBusinessFactoryMock = $this->createDiscountBusinessFactoryMock();
        }

        return new MetaProviderFactory($discountBusinessFactoryMock);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\DiscountBusinessFactory
     */
    protected function createDiscountBusinessFactoryMock()
    {
        return $this->getMockBuilder(DiscountBusinessFactory::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\QueryString\ComparatorOperators
     */
    protected function createComparatorOperatorsMock()
    {
        return $this->getMockBuilder(ComparatorOperators::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\QueryString\LogicalComparators
     */
    protected function createLogicalComparatorsMock()
    {
        return $this->getMockBuilder(LogicalComparators::class)->getMock();
    }
}
