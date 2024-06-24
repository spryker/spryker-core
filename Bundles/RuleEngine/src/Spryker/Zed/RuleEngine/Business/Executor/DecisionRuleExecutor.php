<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Executor;

use Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\RuleEngine\Business\Builder\RuleSpecificationBuilderInterface;
use Spryker\Zed\RuleEngine\RuleEngineConfig;

class DecisionRuleExecutor implements DecisionRuleExecutorInterface
{
    /**
     * @var \Spryker\Zed\RuleEngine\Business\Builder\RuleSpecificationBuilderInterface
     */
    protected RuleSpecificationBuilderInterface $specificationBuilder;

    /**
     * @var \Spryker\Zed\RuleEngine\RuleEngineConfig
     */
    protected RuleEngineConfig $ruleEngineConfig;

    /**
     * @param \Spryker\Zed\RuleEngine\Business\Builder\RuleSpecificationBuilderInterface $specificationBuilder
     * @param \Spryker\Zed\RuleEngine\RuleEngineConfig $ruleEngineConfig
     */
    public function __construct(RuleSpecificationBuilderInterface $specificationBuilder, RuleEngineConfig $ruleEngineConfig)
    {
        $this->specificationBuilder = $specificationBuilder;
        $this->ruleEngineConfig = $ruleEngineConfig;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $satisfyingTransfer
     * @param \Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(
        TransferInterface $satisfyingTransfer,
        RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
    ): bool {
        $ruleEngineSpecificationRequestTransfer->getRuleEngineSpecificationProviderRequestOrFail()
            ->setSpecificationRuleType($this->ruleEngineConfig->getDecisionRuleSpecificationType());

        /** @var \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface $decisionRuleSpecification */
        $decisionRuleSpecification = $this->specificationBuilder->build($ruleEngineSpecificationRequestTransfer);

        return $decisionRuleSpecification->isSatisfiedBy($satisfyingTransfer);
    }
}
