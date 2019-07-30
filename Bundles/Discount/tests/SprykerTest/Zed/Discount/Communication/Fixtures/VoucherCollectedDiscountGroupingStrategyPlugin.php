<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\Communication\Fixtures;

use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Spryker\Zed\DiscountExtension\Dependency\Plugin\CollectedDiscountGroupingStrategyPluginInterface;

class VoucherCollectedDiscountGroupingStrategyPlugin implements CollectedDiscountGroupingStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return bool
     */
    public function isApplicable(CollectedDiscountTransfer $collectedDiscountTransfer): bool
    {
        return $collectedDiscountTransfer->getDiscount()->getVoucherCode() !== null;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getGroupName(): string
    {
        return 'Fixture';
    }
}
