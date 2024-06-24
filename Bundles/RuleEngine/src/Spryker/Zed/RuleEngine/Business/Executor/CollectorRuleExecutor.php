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

class CollectorRuleExecutor implements CollectorRuleExecutorInterface
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
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $collectableTransfer
     * @param \Generated\Shared\Transfer\RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
     *
     * @return list<\Spryker\Shared\Kernel\Transfer\TransferInterface>
     */
    public function collect(
        TransferInterface $collectableTransfer,
        RuleEngineSpecificationRequestTransfer $ruleEngineSpecificationRequestTransfer
    ): array {
        $ruleEngineSpecificationRequestTransfer->getRuleEngineSpecificationProviderRequestOrFail()
            ->setSpecificationRuleType($this->ruleEngineConfig->getCollectorRuleSpecificationType());

        /** @var \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface $collectorRuleSpecification */
        $collectorRuleSpecification = $this->specificationBuilder->build($ruleEngineSpecificationRequestTransfer);

        return $collectorRuleSpecification->collect($collectableTransfer);
    }
}
