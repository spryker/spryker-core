<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\ClauseValidator;
use Spryker\Zed\Discount\Business\QueryString\ClauseValidatorInterface;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface;
use Spryker\Zed\Discount\Business\QueryString\LogicalComparators;
use Spryker\Zed\Discount\Business\QueryString\OperatorProvider;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleProvider;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleAndSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleContext;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleOrSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProvider;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProviderInterface;
use Spryker\Zed\Discount\Business\QueryString\Specification\SpecificationProviderInterface;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder;
use Spryker\Zed\Discount\Business\QueryString\Tokenizer;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\SkuDecisionRulePlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group SpecificationBuilderTest
 * Add your own group annotations below this line
 */
class SpecificationBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testSpecificationBuildSingleClause()
    {
        $decisionRuleSpecificationBuilder = $this->createDecisionRuleSpecificationBuilder();

        $specification = $decisionRuleSpecificationBuilder->buildFromQueryString('sku = "123"');

        $this->assertInstanceOf(DecisionRuleSpecificationInterface::class, $specification);
    }

    /**
     * @return void
     */
    public function testSpecificationBuildMultipleWithBooleanAndShouldReturnAndSpec()
    {
        $decisionRuleSpecificationBuilder = $this->createDecisionRuleSpecificationBuilder();

        $specification = $decisionRuleSpecificationBuilder->buildFromQueryString(
            'sku is in "123' . ComparatorOperators::LIST_DELIMITER . '321" and sku  is in  "321' . ComparatorOperators::LIST_DELIMITER . ' 123"'
        );

        $this->assertInstanceOf(DecisionRuleAndSpecification::class, $specification);
    }

    /**
     * @return void
     */
    public function testSpecificationBuildMultipleWithBooleanOrShouldReturnOrSpec()
    {
        $decisionRuleSpecificationBuilder = $this->createDecisionRuleSpecificationBuilder();

        $specification = $decisionRuleSpecificationBuilder->buildFromQueryString(
            'sku is in "123' . ComparatorOperators::LIST_DELIMITER . '321" or sku  is in  "321' . ComparatorOperators::LIST_DELIMITER . ' 123"'
        );

        $this->assertInstanceOf(DecisionRuleOrSpecification::class, $specification);
    }

    /**
     * @return void
     */
    public function testSpecificationBuildMultipleWithBooleanOrShouldReturnOrSpecs()
    {
        $decisionRuleSpecificationBuilder = $this->createDecisionRuleSpecificationBuilder();

        $specification = $decisionRuleSpecificationBuilder->buildFromQueryString(
            '(sku = "231" or (sku = "1" and  sku = "2")) and sku = "3") '
        );

        $this->assertInstanceOf(DecisionRuleAndSpecification::class, $specification);
    }

    /**
     * @return void
     */
    public function testSpecificationBuildWhenInvalidFieldIsUsedShouldThrowException()
    {
        $this->expectException(QueryStringException::class);

        $decisionRuleSpecificationBuilder = $this->createDecisionRuleSpecificationBuilder();

        $decisionRuleSpecificationBuilder->buildFromQueryString('skus = "123"');
    }

    /**
     * @return void
     */
    public function testSpecificationBuildWhenInvalidComparatorIsUsedShouldThrowException()
    {
        $this->expectException(QueryStringException::class);

        $decisionRuleSpecificationBuilder = $this->createDecisionRuleSpecificationBuilder();

        $decisionRuleSpecificationBuilder->buildFromQueryString('sku compare something "123"');
    }

    /**
     * @return void
     */
    public function testSpecificationBuildWhenInvalidCharactersUsedForFieldShouldThrowException()
    {
        $this->expectException(QueryStringException::class);

        $decisionRuleSpecificationBuilder = $this->createDecisionRuleSpecificationBuilder();

        $decisionRuleSpecificationBuilder->buildFromQueryString('s$ku = "123"');
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder
     */
    protected function createDecisionRuleSpecificationBuilder()
    {
        $comparatorOperators = $this->createComparatorOperators();

        $decisionRuleMetaProvider = new MetaDataProvider(
            $this->createDecisionRulePlugins(),
            $comparatorOperators,
            $this->createLogicalComparators()
        );

        return new SpecificationBuilder(
            $this->createTokenizer(),
            $this->createDecisionRuleProvider(),
            $comparatorOperators,
            $this->createClauseValidator($comparatorOperators, $decisionRuleMetaProvider),
            $this->createMetaDataProvider()
        );
    }

    /**
     * @return array
     */
    protected function createDecisionRulePlugins()
    {
        return [
           new SkuDecisionRulePlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProvider
     */
    protected function createMetaDataProvider()
    {
        return new MetaDataProvider(
            $this->createDecisionRulePlugins(),
            $this->createComparatorOperators(),
            $this->createLogicalComparators()
        );
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators
     */
    protected function createComparatorOperators()
    {
        $operators = (new OperatorProvider())->createComparators();
        $comparatorOperators = new ComparatorOperators($operators);

        return $comparatorOperators;
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\LogicalComparators
     */
    protected function createLogicalComparators()
    {
        return new LogicalComparators();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Tokenizer
     */
    protected function createTokenizer()
    {
        return new Tokenizer();
    }

    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleProvider
     */
    protected function createDecisionRuleProvider()
    {
        return new DecisionRuleProvider($this->createDecisionRulePlugins());
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators $comparatorOperators
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProvider $decisionRuleMetaProvider
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\ClauseValidator
     */
    protected function createClauseValidator(ComparatorOperators $comparatorOperators, MetaDataProvider $decisionRuleMetaProvider)
    {
        return new ClauseValidator($comparatorOperators, $decisionRuleMetaProvider);
    }

    /**
     * @return void
     */
    public function testBuildDecisionRuleWhenOneClauseUsed()
    {
        $specificationProviderMock = $this->createSpecificationProviderMock();
        $specificationProviderMock->expects($this->once())
            ->method('getSpecificationContext')
            ->willReturn($this->createDecisionRuleContextMock());

        $createComparatorOperatorsMock = $this->createComparatorOperatorsMock();
        $createComparatorOperatorsMock->method('isExistingComparator')
            ->willReturnCallback(function (ClauseTransfer $clauseTransfer) {
                return $clauseTransfer->getOperator() === '=' ? true : false;
            });

        $specificationBuilder = $this->createSpecificationBuilder($specificationProviderMock, $createComparatorOperatorsMock);
        $compositeSpecification = $specificationBuilder->buildFromQueryString('sku = "123"');

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

        $createComparatorOperatorsMock = $this->createComparatorOperatorsMock();
        $createComparatorOperatorsMock->method('isExistingComparator')
            ->willReturnCallback(function (ClauseTransfer $clauseTransfer) {
                return $clauseTransfer->getOperator() === '=' ? true : false;
            });

        $specificationBuilder = $this->createSpecificationBuilder($specificationProviderMock, $createComparatorOperatorsMock);
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

        $createComparatorOperatorsMock = $this->createComparatorOperatorsMock();
        $createComparatorOperatorsMock->method('isExistingComparator')
            ->willReturnCallback(function (ClauseTransfer $clauseTransfer) {
                return $clauseTransfer->getOperator() === '=' ? true : false;
            });

        $specificationBuilder = $this->createSpecificationBuilder($specificationProviderMock, $createComparatorOperatorsMock);
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

        $createComparatorOperatorsMock = $this->createComparatorOperatorsMock();
        $createComparatorOperatorsMock->method('isExistingComparator')
            ->willReturnCallback(function (ClauseTransfer $clauseTransfer) {
                if ($clauseTransfer->getOperator() === '=' || $clauseTransfer->getOperator() === 'is in') {
                    return true;
                }

                return false;
            });

        $specificationBuilder = $this->createSpecificationBuilder($specificationProviderMock, $createComparatorOperatorsMock);
        $compositeSpecification = $specificationBuilder->buildFromQueryString(
            '((sku = "123" and (quantity is in "321' . ComparatorOperators::LIST_DELIMITER . '321" or sku = "123"))) or color = "red"'
        );

        $this->assertInstanceOf(DecisionRuleSpecificationInterface::class, $compositeSpecification);
    }

    /**
     * @return void
     */
    public function testBuildDecisionRuleWithAttributeClauseShouldBuildClauseWithAdditionalAttributeData()
    {
        $specificationProviderMock = $this->createSpecificationProviderMock();
        $specificationProviderMock->expects($this->exactly(1))
            ->method('getSpecificationContext')
            ->willReturn($this->createDecisionRuleContextMock());

        $createComparatorOperatorsMock = $this->createComparatorOperatorsMock();
        $createComparatorOperatorsMock->method('isExistingComparator')
            ->willReturnCallback(function (ClauseTransfer $clauseTransfer) {
                if ($clauseTransfer->getOperator() === '=') {
                    return true;
                }

                return false;
            });

        $specificationBuilder = $this->createSpecificationBuilder($specificationProviderMock, $createComparatorOperatorsMock);
        $compositeSpecification = $specificationBuilder->buildFromQueryString(
            'attribute.value = "123"'
        );

        $this->assertInstanceOf(DecisionRuleSpecificationInterface::class, $compositeSpecification);
    }

    /**
     * @return void
     */
    public function testBuildDecisionRuleWhenIncompleteQueryStringGivenShouldThrowException()
    {
        $this->expectException(QueryStringException::class);

        $createComparatorOperatorsMock = $this->createComparatorOperatorsMock();
        $createComparatorOperatorsMock->method('isExistingComparator')
            ->willReturnCallback(function (ClauseTransfer $clauseTransfer) {
                if ($clauseTransfer->getOperator() === '=') {
                    return true;
                }

                return false;
            });

        $specificationProviderMock = $this->createSpecificationProviderMock();
        $specificationBuilder = $this->createSpecificationBuilder($specificationProviderMock, $createComparatorOperatorsMock);
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
     * @return void
     */
    public function testBuildDecisionRuleWhenNumberOfParenthesisNotMatchingShouldThrowException()
    {
        $this->expectException(QueryStringException::class);

        $createComparatorOperatorsMock = $this->createComparatorOperatorsMock();
        $createComparatorOperatorsMock->method('isExistingComparator')
            ->willReturnCallback(function (ClauseTransfer $clauseTransfer) {
                if ($clauseTransfer->getOperator() === '=') {
                    return true;
                }

                return false;
            });

        $specificationProviderMock = $this->createSpecificationProviderMock();
        $specificationBuilder = $this->createSpecificationBuilder($specificationProviderMock, $createComparatorOperatorsMock);
        $specificationBuilder->buildFromQueryString('(sku = 123');
    }

    /**
     * @see MetaDataProviderInterface::isFieldAvailable()
     *
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\SpecificationProviderInterface $specificationProviderMock
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface|null $createComparatorOperatorsMock
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProviderInterface|null $metaDataProviderMock
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder
     */
    public function createSpecificationBuilder(
        SpecificationProviderInterface $specificationProviderMock,
        ?ComparatorOperatorsInterface $createComparatorOperatorsMock = null,
        ?MetaDataProviderInterface $metaDataProviderMock = null
    ) {

        if ($createComparatorOperatorsMock === null) {
            $createComparatorOperatorsMock = $this->createComparatorOperatorsMock();
        }

        if ($metaDataProviderMock === null) {
            $metaDataProviderMock = $this->createMetaDataProviderMock();
            $metaDataProviderMock
                ->expects($this->any())
                ->method('isFieldAvailable')
                ->will($this->returnValueMap([
                    ['quantity', true],
                    ['sku', true],
                    ['color', true],
                    ['attribute.value', true],
                ]));
        }

        return new SpecificationBuilder(
            $this->createTokenizer(),
            $specificationProviderMock,
            $createComparatorOperatorsMock,
            $this->createClauseValidatorMock(),
            $metaDataProviderMock
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProviderInterface
     */
    protected function createMetaDataProviderMock()
    {
        return $this->getMockBuilder(MetaDataProviderInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\QueryString\ClauseValidatorInterface
     */
    protected function createClauseValidatorMock()
    {
        return $this->getMockBuilder(ClauseValidatorInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface
     */
    protected function createComparatorOperatorsMock()
    {
        $createComparatorOperatorsMock = $this->getMockBuilder(ComparatorOperatorsInterface::class)->getMock();

        $createComparatorOperatorsMock->method('getCompoundComparatorExpressions')->willReturn(['is', 'in']);

        return $createComparatorOperatorsMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Discount\Business\QueryString\Specification\SpecificationProviderInterface
     */
    protected function createSpecificationProviderMock()
    {
        $specificationProviderMock = $this->getMockBuilder(SpecificationProviderInterface::class)->getMock();

        return $specificationProviderMock;
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
        return $this->getMockBuilder(DecisionRuleSpecificationInterface::class)->getMock();
    }
}
