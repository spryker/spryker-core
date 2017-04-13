<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Business\QueryString;

use Codeception\TestCase\Test;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\ClauseValidator;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Business\QueryString\LogicalComparators;
use Spryker\Zed\Discount\Business\QueryString\OperatorProvider;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleProvider;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleAndSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleOrSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProvider;
use Spryker\Zed\Discount\Business\QueryString\Tokenizer;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\SkuDecisionRulePlugin;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Discount
 * @group Business
 * @group QueryString
 * @group SpecificationBuilderTest
 */
class SpecificationBuilderTest extends Test
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
           new SkuDecisionRulePlugin()
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

}
