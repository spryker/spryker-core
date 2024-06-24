<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\RuleEngine\Business\Builder\RuleSpecificationBuilder;
use Spryker\Zed\RuleEngine\Business\Builder\RuleSpecificationBuilderInterface;
use Spryker\Zed\RuleEngine\Business\Comparator\Comparator;
use Spryker\Zed\RuleEngine\Business\Comparator\ComparatorChecker;
use Spryker\Zed\RuleEngine\Business\Comparator\ComparatorCheckerInterface;
use Spryker\Zed\RuleEngine\Business\Comparator\ComparatorInterface;
use Spryker\Zed\RuleEngine\Business\Comparator\Operator\CompareOperatorInterface;
use Spryker\Zed\RuleEngine\Business\Comparator\Operator\ContainsCompareOperator;
use Spryker\Zed\RuleEngine\Business\Comparator\Operator\DoesNotContainCompareOperator;
use Spryker\Zed\RuleEngine\Business\Comparator\Operator\EqualCompareOperator;
use Spryker\Zed\RuleEngine\Business\Comparator\Operator\GreaterCompareOperator;
use Spryker\Zed\RuleEngine\Business\Comparator\Operator\GreaterEqualCompareOperator;
use Spryker\Zed\RuleEngine\Business\Comparator\Operator\IsInCompareOperator;
use Spryker\Zed\RuleEngine\Business\Comparator\Operator\IsNotInCompareOperator;
use Spryker\Zed\RuleEngine\Business\Comparator\Operator\LessCompareOperator;
use Spryker\Zed\RuleEngine\Business\Comparator\Operator\LessEqualCompareOperator;
use Spryker\Zed\RuleEngine\Business\Comparator\Operator\NotEqualCompareOperator;
use Spryker\Zed\RuleEngine\Business\Executor\CollectorRuleExecutor;
use Spryker\Zed\RuleEngine\Business\Executor\CollectorRuleExecutorInterface;
use Spryker\Zed\RuleEngine\Business\Executor\DecisionRuleExecutor;
use Spryker\Zed\RuleEngine\Business\Executor\DecisionRuleExecutorInterface;
use Spryker\Zed\RuleEngine\Business\Resolver\RuleSpecificationProviderResolver;
use Spryker\Zed\RuleEngine\Business\Resolver\RuleSpecificationProviderResolverInterface;
use Spryker\Zed\RuleEngine\Business\Specification\MetaData\MetaDataProvider;
use Spryker\Zed\RuleEngine\Business\Specification\MetaData\MetaDataProviderInterface;
use Spryker\Zed\RuleEngine\Business\Tokenizer\Tokenizer;
use Spryker\Zed\RuleEngine\Business\Tokenizer\TokenizerInterface;
use Spryker\Zed\RuleEngine\Business\Validator\ClauseValidator;
use Spryker\Zed\RuleEngine\Business\Validator\ClauseValidatorInterface;
use Spryker\Zed\RuleEngine\Business\Validator\QueryStringValidator;
use Spryker\Zed\RuleEngine\Business\Validator\QueryStringValidatorInterface;
use Spryker\Zed\RuleEngine\RuleEngineDependencyProvider;

/**
 * @method \Spryker\Zed\RuleEngine\RuleEngineConfig getConfig()
 */
class RuleEngineBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\RuleEngine\Business\Executor\CollectorRuleExecutorInterface
     */
    public function createCollectorRuleExecutor(): CollectorRuleExecutorInterface
    {
        return new CollectorRuleExecutor(
            $this->createRuleSpecificationBuilder(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Executor\DecisionRuleExecutorInterface
     */
    public function createDecisionRuleExecutor(): DecisionRuleExecutorInterface
    {
        return new DecisionRuleExecutor(
            $this->createRuleSpecificationBuilder(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Builder\RuleSpecificationBuilderInterface
     */
    public function createRuleSpecificationBuilder(): RuleSpecificationBuilderInterface
    {
        return new RuleSpecificationBuilder(
            $this->createTokenizer(),
            $this->createRuleSpecificationProviderResolver(),
            $this->createComparatorChecker(),
            $this->createClauseValidator(),
            $this->createMetaDataProvider(),
        );
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Tokenizer\TokenizerInterface
     */
    public function createTokenizer(): TokenizerInterface
    {
        return new Tokenizer();
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Resolver\RuleSpecificationProviderResolverInterface
     */
    public function createRuleSpecificationProviderResolver(): RuleSpecificationProviderResolverInterface
    {
        return new RuleSpecificationProviderResolver($this->getRuleSpecificationProviderPlugins());
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Validator\ClauseValidatorInterface
     */
    public function createClauseValidator(): ClauseValidatorInterface
    {
        return new ClauseValidator(
            $this->createComparatorChecker(),
            $this->createMetaDataProvider(),
        );
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Validator\QueryStringValidatorInterface
     */
    public function createQueryStringValidator(): QueryStringValidatorInterface
    {
        return new QueryStringValidator($this->createRuleSpecificationBuilder());
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Specification\MetaData\MetaDataProviderInterface
     */
    public function createMetaDataProvider(): MetaDataProviderInterface
    {
        return new MetaDataProvider();
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Comparator\ComparatorInterface
     */
    public function createComparator(): ComparatorInterface
    {
        return new Comparator($this->getCompareOperators());
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Comparator\ComparatorCheckerInterface
     */
    public function createComparatorChecker(): ComparatorCheckerInterface
    {
        return new ComparatorChecker($this->getCompareOperators());
    }

    /**
     * @return list<\Spryker\Zed\RuleEngine\Business\Comparator\Operator\CompareOperatorInterface>
     */
    public function getCompareOperators(): array
    {
        return [
            $this->createContainsCompareOperator(),
            $this->createDoesNotContainCompareOperator(),
            $this->createEqualCompareOperator(),
            $this->createGreaterCompareOperator(),
            $this->createGreaterEqualCompareOperator(),
            $this->createIsInCompareOperator(),
            $this->createIsNotInCompareOperator(),
            $this->createLessCompareOperator(),
            $this->createLessEqualCompareOperator(),
            $this->createNotEqualCompareOperator(),
        ];
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Comparator\Operator\CompareOperatorInterface
     */
    public function createContainsCompareOperator(): CompareOperatorInterface
    {
        return new ContainsCompareOperator();
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Comparator\Operator\CompareOperatorInterface
     */
    public function createDoesNotContainCompareOperator(): CompareOperatorInterface
    {
        return new DoesNotContainCompareOperator();
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Comparator\Operator\CompareOperatorInterface
     */
    public function createEqualCompareOperator(): CompareOperatorInterface
    {
        return new EqualCompareOperator();
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Comparator\Operator\CompareOperatorInterface
     */
    public function createGreaterCompareOperator(): CompareOperatorInterface
    {
        return new GreaterCompareOperator();
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Comparator\Operator\CompareOperatorInterface
     */
    public function createGreaterEqualCompareOperator(): CompareOperatorInterface
    {
        return new GreaterEqualCompareOperator();
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Comparator\Operator\CompareOperatorInterface
     */
    public function createIsInCompareOperator(): CompareOperatorInterface
    {
        return new IsInCompareOperator();
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Comparator\Operator\CompareOperatorInterface
     */
    public function createIsNotInCompareOperator(): CompareOperatorInterface
    {
        return new IsNotInCompareOperator();
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Comparator\Operator\CompareOperatorInterface
     */
    public function createLessCompareOperator(): CompareOperatorInterface
    {
        return new LessCompareOperator();
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Comparator\Operator\CompareOperatorInterface
     */
    public function createLessEqualCompareOperator(): CompareOperatorInterface
    {
        return new LessEqualCompareOperator();
    }

    /**
     * @return \Spryker\Zed\RuleEngine\Business\Comparator\Operator\CompareOperatorInterface
     */
    public function createNotEqualCompareOperator(): CompareOperatorInterface
    {
        return new NotEqualCompareOperator();
    }

    /**
     * @return list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\RuleSpecificationProviderPluginInterface>
     */
    public function getRuleSpecificationProviderPlugins(): array
    {
        return $this->getProvidedDependency(RuleEngineDependencyProvider::PLUGINS_RULE_SPECIFICATION_PROVIDER);
    }
}
