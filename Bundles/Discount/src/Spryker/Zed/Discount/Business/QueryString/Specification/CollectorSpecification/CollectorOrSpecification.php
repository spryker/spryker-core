<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification;

use Generated\Shared\Transfer\QuoteTransfer;

class CollectorOrSpecification implements CollectorSpecificationInterface
{
    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface
     */
    protected $left;

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface
     */
    protected $right;

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface $left
     * @param \Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification\CollectorSpecificationInterface $right
     */
    public function __construct(CollectorSpecificationInterface $left, CollectorSpecificationInterface $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collect(QuoteTransfer $quoteTransfer)
    {
        $leftCollectedItems = $this->left->collect($quoteTransfer);
        $rightCollectedItems = $this->right->collect($quoteTransfer);

        return $this->arrayMergeByObject($leftCollectedItems, $rightCollectedItems);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $leftArray
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $rightArray
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    protected function arrayMergeByObject(array $leftArray, array $rightArray)
    {
        if (count($leftArray) === 0) {
            return $rightArray;
        }

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
