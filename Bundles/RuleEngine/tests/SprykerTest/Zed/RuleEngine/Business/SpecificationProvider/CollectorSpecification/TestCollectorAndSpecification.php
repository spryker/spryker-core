<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RuleEngine\Business\SpecificationProvider\CollectorSpecification;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\RuleSpecificationInterface;

class TestCollectorAndSpecification implements CollectorRuleSpecificationInterface
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
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $collectableTransfer
     *
     * @return list<\Spryker\Shared\Kernel\Transfer\TransferInterface>
     */
    public function collect(TransferInterface $collectableTransfer): array
    {
        $lefCollectedItems = $this->leftNode->collect($collectableTransfer);
        $rightCollectedItems = $this->rightNode->collect($collectableTransfer);

        return array_uintersect(
            $lefCollectedItems,
            $rightCollectedItems,
            function (TransferInterface $collected, TransferInterface $toCollect) {
                return strcmp(spl_object_hash($collected), spl_object_hash($toCollect));
            },
        );
    }
}
