<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString\Specification\CollectorSpecification;

use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class CollectorAndSpecification implements CollectorSpecificationInterface
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
        $lefCollectedItems = $this->left->collect($quoteTransfer);
        $rightCollectedItems = $this->right->collect($quoteTransfer);

        return array_uintersect(
            $lefCollectedItems,
            $rightCollectedItems,
            function (DiscountableItemTransfer $collected, DiscountableItemTransfer $toCollect) {
                return strcmp(spl_object_hash($collected->getOriginalItemCalculatedDiscounts()), spl_object_hash($toCollect->getOriginalItemCalculatedDiscounts()));
            }
        );
    }
}
