<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface DiscountApplicableFilterPluginInterface
{
    /**
     * Specification:
     *  - With this filter plugin you filter plugins before applying decision rules so that they are ignored and wont be used when matching.
     *  - Each filter should narrow down the $discountApplicableItems object collection
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $discountApplicableItems
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function filter(array $discountApplicableItems, QuoteTransfer $quoteTransfer, $idDiscount);
}
