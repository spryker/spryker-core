<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Specification;

use Generated\Shared\Transfer\ClauseTransfer;
use Spryker\Zed\Discount\Business\Exception\QueryStringException;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleAndSpecification;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleContext;
use Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleOrSpecification;
use Spryker\Zed\Discount\DiscountDependencyProvider;

class DecisionRuleProvider extends BaseSpecificationProvider implements SpecificationProviderInterface
{

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface[]
     */
    protected $decisionRulePlugins = [];

    /**
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DecisionRulePluginInterface[] $collectorPlugins
     */
    public function __construct(array $collectorPlugins)
    {
        $this->decisionRulePlugins = $collectorPlugins;
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface $left
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface $right
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface
     */
    public function createAnd($left, $right)
    {
        return new DecisionRuleAndSpecification($left, $right);
    }

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface $left
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface $right
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface
     */
    public function createOr($left, $right)
    {
        return new DecisionRuleOrSpecification($left, $right);
    }

    /**
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Spryker\Zed\Discount\Business\QueryString\Specification\DecisionRuleSpecification\DecisionRuleSpecificationInterface
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\QueryStringException
     */
    public function getSpecificationContext(ClauseTransfer $clauseTransfer)
    {
        foreach ($this->decisionRulePlugins as $decisionRulePlugin) {

            $clauseFieldName = $this->getClauseFieldName($clauseTransfer);

            if (strcasecmp($decisionRulePlugin->getFieldName(), $clauseFieldName) === 0) {
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
