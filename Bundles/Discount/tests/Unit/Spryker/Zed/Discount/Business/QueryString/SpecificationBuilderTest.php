<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Discount\Business\QueryString;

use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\ClauseValidatorInterface;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleContext;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface;
use Spryker\Zed\Discount\Business\QueryString\Specification\SpecificationProviderInterface;
use Spryker\Zed\Discount\Business\QueryString\Tokenizer;

class SpecificationBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testBuildDecisionRuleWhenOneClauseUsed()
    {
        $specificationProviderMock = $this->createSpecificationProviderMock();
        $specificationProviderMock->expects($this->once())
            ->method('getSpecificationContext')
            ->willReturn($this->createDecisionRuleContextMock());

        $specificationBuilder = $this->createSpecificationBuilder($specificationProviderMock);
        $compositeSpecification = $specificationBuilder->buildFromQueryString('sku = 123');

        $this->assertInstanceOf(DecisionRuleSpecificationInterface::class, $compositeSpecification);
    }

    /**
     * @return void
     */
    public function testBuildDecisionRuleWhenOrLogicalComparatorUsed()
    {
        $specificationProviderMock = $this->createSpecificationProviderMock();
        $specificationProviderMock->expects($this->exactly(2))
            ->method('getSpecificationContext')
            ->willReturn($this->createDecisionRuleContextMock());

        $specificationProviderMock->expects($this->once())
            ->method('createOr');

        $specificationBuilder = $this->createSpecificationBuilder($specificationProviderMock);
        $compositeSpecification = $specificationBuilder->buildFromQueryString('sku = "123" or quantity = "321"');

        $this->assertInstanceOf(DecisionRuleSpecificationInterface::class, $compositeSpecification);
    }

    /**
     * @return void
     */
    public function testBuildDecisionRuleWhenAndLogicalComparatorUsed()
    {
        $specificationProviderMock = $this->createSpecificationProviderMock();
        $specificationProviderMock->expects($this->exactly(2))
            ->method('getSpecificationContext')
            ->willReturn($this->createDecisionRuleContextMock());

        $specificationProviderMock->expects($this->once())
            ->method('createAnd');

        $specificationBuilder = $this->createSpecificationBuilder($specificationProviderMock);
        $compositeSpecification = $specificationBuilder->buildFromQueryString('sku = "123" and quantity = "321"');

        $this->assertInstanceOf(DecisionRuleSpecificationInterface::class, $compositeSpecification);
    }

    /**
     * @return void
     */
    public function testBuildDecisionRuleWhenMultipleParenthesisUsed()
    {
        $specificationProviderMock = $this->createSpecificationProviderMock();
        $specificationProviderMock->expects($this->exactly(4))
            ->method('getSpecificationContext')
            ->willReturn($this->createDecisionRuleContextMock());

        $specificationProviderMock->expects($this->once())
            ->method('createAnd')
            ->willReturn($this->createDecisionRuleSpecificationMock());

        $specificationProviderMock->expects($this->exactly(2))
            ->method('createOr')
            ->willReturn($this->createDecisionRuleSpecificationMock());

        $specificationBuilder = $this->createSpecificationBuilder($specificationProviderMock);
        $compositeSpecification = $specificationBuilder->buildFromQueryString(
            '(sku = "123" and (quantity = "321" or sku = "123")) or color = "red"'
        );

        $this->assertInstanceOf(DecisionRuleSpecificationInterface::class, $compositeSpecification);
    }

    /**
     * @return void
     */
    public function testBuildDecisionRuleWhenIncompleteQueryStringGivenShouldThrowException()
    {
        $this->expectException(QueryStringException::class);

        $specificationProviderMock = $this->createSpecificationProviderMock();

        $specificationBuilder = $this->createSpecificationBuilder($specificationProviderMock);
        $compositeSpecification = $specificationBuilder->buildFromQueryString('(sku = ');

        $this->assertInstanceOf(DecisionRuleSpecificationInterface::class, $compositeSpecification);
    }

    /**
     * @return void
     */
    public function testBuildDecisionRuleWhenEmptyQueryStringGivenShouldThrowException()
    {
        $this->expectException(QueryStringException::class);

        $specificationProviderMock = $this->createSpecificationProviderMock();

        $specificationBuilder = $this->createSpecificationBuilder($specificationProviderMock);
        $compositeSpecification = $specificationBuilder->buildFromQueryString('');

        $this->assertInstanceOf(DecisionRuleSpecificationInterface::class, $compositeSpecification);
    }


    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\SpecificationProviderInterface $specificationProviderMock
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder
     */
    public function createSpecificationBuilder(SpecificationProviderInterface $specificationProviderMock)
    {
        $createComparatorOperatorsMock = $this->createComparatorOperatorsMock();
        $createComparatorOperatorsMock->method('getCompoundComparatorExpressions')
            ->willReturn([]);

        return new SpecificationBuilder(
            $this->createTokenizer(),
            $specificationProviderMock,
            $createComparatorOperatorsMock,
            $this->createClauseValidatorMock()
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\QueryString\ClauseValidatorInterface
     */
    protected function createClauseValidatorMock()
    {
        return $this->getMock(ClauseValidatorInterface::class);
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Tokenizer
     */
    protected function createTokenizer()
    {
        return new Tokenizer();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface
     */
    protected function createComparatorOperatorsMock()
    {
        return $this->getMock(ComparatorOperatorsInterface::class);
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\QueryString\Specification\SpecificationProviderInterface
     */
    protected function createSpecificationProviderMock()
    {
        return $this->getMock(SpecificationProviderInterface::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleContext
     */
    protected function createDecisionRuleContextMock()
    {
        return $this->getMockBuilder(DecisionRuleContext::class)
            ->disableOriginalConstructor()
            ->getMock();

    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface
     */
    protected function createDecisionRuleSpecificationMock()
    {
        return $this->getMock(DecisionRuleSpecificationInterface::class);
    }

}
