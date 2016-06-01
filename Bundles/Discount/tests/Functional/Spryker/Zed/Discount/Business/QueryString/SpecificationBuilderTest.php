<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Discount\Business\QueryString;

use Codeception\TestCase\Test;
use Spryker\Zed\Discount\Business\QueryString\ClauseValidator;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;
use Spryker\Zed\Discount\Business\QueryString\LogicalComparators;
use Spryker\Zed\Discount\Business\QueryString\OperatorProvider;
use Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleProvider;
use Spryker\Zed\Discount\Business\QueryString\Specification\MetaData\MetaDataProvider;
use Spryker\Zed\Discount\Business\QueryString\Tokenizer;
use Spryker\Zed\Discount\Communication\Plugin\DecisionRule\SkuDecisionRulePlugin;

class SpecificationBuilderTest extends Test
{

    /**
     * @return void
     */
    public function testSpecificationBuild()
    {
        $decisionRuleSpecificationBuilder = $this->createDecisionRuleSpecificationBuilder();

        $spec = $decisionRuleSpecificationBuilder->buildFromQueryString('(sku  is not in 321 or sku = "0") and sku = "444"');
    }


    /**
     * @return \Spryker\Zed\Discount\Business\QueryString\SpecificationBuilder
     */
    protected function createDecisionRuleSpecificationBuilder()
    {
        $operators = (new OperatorProvider())->createComparators();
        $comparatorOperators = new ComparatorOperators($operators);

        $decisionRuleMetaProvider =  new MetaDataProvider(
            $this->createDecisionRulePlugins(),
            $comparatorOperators,
            new LogicalComparators()
        );

        return new SpecificationBuilder(
            new Tokenizer(),
            new DecisionRuleProvider($this->createDecisionRulePlugins()),
            $comparatorOperators,
            new ClauseValidator($comparatorOperators, $decisionRuleMetaProvider)
        );
    }

    protected function createDecisionRulePlugins()
    {
        return [
           new SkuDecisionRulePlugin()
        ];
    }

}
