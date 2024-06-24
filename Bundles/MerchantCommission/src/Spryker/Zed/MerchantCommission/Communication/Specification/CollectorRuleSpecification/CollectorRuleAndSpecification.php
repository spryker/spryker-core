<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Communication\Specification\CollectorRuleSpecification;

use Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface;

class CollectorRuleAndSpecification implements CollectorRuleSpecificationInterface
{
    /**
     * @var \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface
     */
    protected CollectorRuleSpecificationInterface $leftNode;

    /**
     * @var \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface
     */
    protected CollectorRuleSpecificationInterface $rightNode;

    /**
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface $leftNode
     * @param \Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface $rightNode
     */
    public function __construct(CollectorRuleSpecificationInterface $leftNode, CollectorRuleSpecificationInterface $rightNode)
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
            function (MerchantCommissionCalculationRequestItemTransfer $collected, MerchantCommissionCalculationRequestItemTransfer $toCollect) {
                return strcmp(spl_object_hash($collected->getMerchantCommissions()), spl_object_hash($toCollect->getMerchantCommissions()));
            },
        );
    }
}
