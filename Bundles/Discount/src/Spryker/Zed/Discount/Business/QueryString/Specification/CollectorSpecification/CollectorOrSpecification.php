<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class CollectorOrSpecification implements CollectorSpecificationInterface
{
    /**
     * @var CollectorSpecificationInterface
     */
    protected $left;

    /**
     * @var CollectorSpecificationInterface
     */
    protected $right;

    /**
     * @param CollectorSpecificationInterface $left
     * @param CollectorSpecificationInterface $right
     */
    public function __construct(
        CollectorSpecificationInterface $left,
        CollectorSpecificationInterface $right
    ) {
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function collect(QuoteTransfer $quoteTransfer)
    {
        $leftCollectedItems = $this->left->collect($quoteTransfer);
        $rightCollectedItems = $this->right->collect($quoteTransfer);

        return $this->array_merge_by_object($leftCollectedItems, $rightCollectedItems);
    }

    /**
     * @param DiscountableItemTransfer[] $leftArray
     * @param DiscountableItemTransfer[] $rightArray
     *
     * @return array
     */
    protected function array_merge_by_object(array $leftArray, array $rightArray)
    {
        $merged = [];
        foreach ($leftArray as $leftItem) {
            $leftItemHash = spl_object_hash($leftItem->getOriginalItemCalculatedDiscounts());
            $merged[$leftItemHash] = $leftItem;
            foreach ($rightArray as $rightItem) {
                $rightItemHash = spl_object_hash($rightItem->getOriginalItemCalculatedDiscounts());
                if (isset($merged[$rightItemHash])) {
                    continue;
                }
                $merged[$rightItemHash] = $rightItem;
            }
        }

        return $merged;
    }
}
