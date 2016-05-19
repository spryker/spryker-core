<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Specification;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleAndSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleOrSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface;
use Spryker\Zed\Discount\DiscountDependencyProvider;

class DecisionRuleProvider implements SpecificationProviderInterface
{
    /**
     * @var DecisionRulePluginInterface[]
     */
    protected $decisionRulePlugins = [];

    /**
     * @param DecisionRulePluginInterface[] $collectorPlugins
     */
    public function __construct(array $collectorPlugins)
    {
        $this->decisionRulePlugins = $collectorPlugins;
    }

    /**
     * @param DecisionRuleSpecificationInterface $left
     * @param DecisionRuleSpecificationInterface $right
     *
     * @return DecisionRuleSpecificationInterface
     */
    public function createAnd($left, $right)
    {
        return new DecisionRuleAndSpecification($left, $right);
    }

    /**
     * @param DecisionRuleSpecificationInterface $left
     * @param DecisionRuleSpecificationInterface $right
     *
     * @return DecisionRuleSpecificationInterface
     */
    public function createOr($left, $right)
    {
        return new DecisionRuleOrSpecification($left, $right);
    }

    /**
     * @param ClauseTransfer $clauseTransfer
     *
     * @return DecisionRuleSpecificationInterface
     *
     * @throws QueryStringException
     */
    public function getSpecificationContext(ClauseTransfer $clauseTransfer)
    {
        foreach ($this->decisionRulePlugins as $decisionRulePlugin) {
            if (strcasecmp($decisionRulePlugin->getFieldName(), $clauseTransfer->getField()) === 0) {
                return new DecisionRuleContext($decisionRulePlugin, $clauseTransfer);
            }
        }

        throw new QueryStringException(
            sprintf(
                'Could not find decision rule plugin for "%s" field. Have you registered it in "%s::getDecisionRulePlugins" plugins stack?',
                $clauseTransfer->getField(),
                DiscountDependencyProvider::class
            )
        );
    }
}
