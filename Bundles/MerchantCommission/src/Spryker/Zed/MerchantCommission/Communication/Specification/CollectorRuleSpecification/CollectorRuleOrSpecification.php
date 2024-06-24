<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Communication\Specification\CollectorRuleSpecification;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\RuleEngineExtension\Communication\Dependency\Specification\CollectorRuleSpecificationInterface;

class CollectorRuleOrSpecification implements CollectorRuleSpecificationInterface
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
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $collectableTransfer
     *
     * @return list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>
     */
    public function collect(TransferInterface $collectableTransfer): array
    {
        /** @var list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer> $leftCollectedItems */
        $leftCollectedItems = $this->leftNode->collect($collectableTransfer);
        /** @var list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer> $rightCollectedItems */
        $rightCollectedItems = $this->rightNode->collect($collectableTransfer);

        return $this->arrayMergeByObject($leftCollectedItems, $rightCollectedItems);
    }

    /**
     * @param list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer> $leftArray
     * @param list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer> $rightArray
     *
     * @return list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>
     */
    protected function arrayMergeByObject(array $leftArray, array $rightArray): array
    {
        if ($leftArray === []) {
            return $rightArray;
        }

        $merged = [];
        foreach ($leftArray as $leftItem) {
            $leftItemHash = spl_object_hash($leftItem->getMerchantCommissions());
            $merged[$leftItemHash] = $leftItem;
            foreach ($rightArray as $rightItem) {
                $rightItemHash = spl_object_hash($rightItem->getMerchantCommissions());
                if (isset($merged[$rightItemHash])) {
                    continue;
                }
                $merged[$rightItemHash] = $rightItem;
            }
        }

        return array_values($merged);
    }
}
