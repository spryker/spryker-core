<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RuleEngine\Business\SpecificationProvider\CollectorSpecification;

use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface;

class TestCollectorContext implements CollectorRuleSpecificationInterface
{
    /**
     * @var \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface
     */
    protected CollectorRulePluginInterface $collectorRulePlugin;

    /**
     * @var \Generated\Shared\Transfer\RuleEngineClauseTransfer
     */
    protected RuleEngineClauseTransfer $ruleEngineClauseTransfer;

    /**
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface $collectorRulePlugin
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     */
    public function __construct(
        CollectorRulePluginInterface $collectorRulePlugin,
        RuleEngineClauseTransfer $ruleEngineClauseTransfer
    ) {
        $this->collectorRulePlugin = $collectorRulePlugin;
        $this->ruleEngineClauseTransfer = $ruleEngineClauseTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $collectableTransfer
     *
     * @return list<\Spryker\Shared\Kernel\Transfer\TransferInterface>
     */
    public function collect(TransferInterface $collectableTransfer): array
    {
        $this->ruleEngineClauseTransfer->setAcceptedTypes($this->collectorRulePlugin->acceptedDataTypes());

        return $this->collectorRulePlugin->collect($collectableTransfer, $this->ruleEngineClauseTransfer);
    }
}
