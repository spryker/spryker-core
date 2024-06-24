<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Communication\Specification\DecisionRuleSpecification;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface;

class DecisionRuleOrSpecification implements DecisionRuleSpecificationInterface
{
    /**
     * @var \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface
     */
    protected DecisionRuleSpecificationInterface $leftNode;

    /**
     * @var \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface
     */
    protected DecisionRuleSpecificationInterface $rightNode;

    /**
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface $leftNode
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\DecisionRuleSpecificationInterface $rightNode
     */
    public function __construct(
        DecisionRuleSpecificationInterface $leftNode,
        DecisionRuleSpecificationInterface $rightNode
    ) {
        $this->leftNode = $leftNode;
        $this->rightNode = $rightNode;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $satisfyingTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(TransferInterface $satisfyingTransfer): bool
    {
        return $this->leftNode->isSatisfiedBy($satisfyingTransfer) || $this->rightNode->isSatisfiedBy($satisfyingTransfer);
    }
}
