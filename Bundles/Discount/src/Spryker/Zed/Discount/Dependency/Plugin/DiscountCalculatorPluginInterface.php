<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Transfer\DiscountTransfer;

interface DiscountCalculatorPluginInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return int
     */
    public function calculateDiscount(array $discountableItems, DiscountTransfer $discountTransfer);

    /**
     * @api
     *
     * @param float $value
     *
     * @return int
     */
    public function transformForPersistence($value);

    /**
     * @api
     *
     * @param int $value
     *
     * @return int
     */
    public function transformFromPersistence($value);

    /**
     * @api
     *
     * @param int $amount
     * @param string|null $isoCode
     *
     * @return string
     */
    public function getFormattedAmount($amount, $isoCode = null);

    /**
     * @api
     *
     * @return array
     */
    public function getAmountValidators();

}
