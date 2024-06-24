<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RuleEngine\Business\SpecificationProvider\CollectorSpecification;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface;

class TestCollectorOrSpecification implements CollectorRuleSpecificationInterface
{
    /**
     * @var \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface
     */
    protected RuleSpecificationInterface $leftNode;

    /**
     * @var \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface
     */
    protected RuleSpecificationInterface $rightNode;

    /**
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface $leftNode
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface $rightNode
     */
    public function __construct(RuleSpecificationInterface $leftNode, RuleSpecificationInterface $rightNode)
    {
        $this->leftNode = $leftNode;
        $this->rightNode = $rightNode;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $collectableTransfer
     *
     * @return array<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>
     */
    public function collect(TransferInterface $collectableTransfer): array
    {
        $leftCollectedItems = $this->leftNode->collect($collectableTransfer);
        $rightCollectedItems = $this->rightNode->collect($collectableTransfer);

        return array_merge($leftCollectedItems, $rightCollectedItems);
    }
}
