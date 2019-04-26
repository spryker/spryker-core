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
     * Specification:
     * - Calculates a discount based on the provided parameters.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountableItemTransfer[] $discountableItems
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return int
     */
    public function calculateDiscount(array $discountableItems, DiscountTransfer $discountTransfer);

    /**
     * Specification:
     * - Converts provided business logic unit to Persistance's unit.
     *
     * @api
     *
     * @param float $value
     *
     * @return int
     */
    public function transformForPersistence($value);

    /**
     * Specification:
     * - Converts provided Persistance's unit to business logic unit.
     *
     * @api
     *
     * @param int $value
     *
     * @return float
     */
    public function transformFromPersistence($value);

    /**
     * Specification:
     * - Formats provided amount.
     *
     * @api
     *
     * @param int $amount
     * @param string|null $isoCode
     *
     * @return string
     */
    public function getFormattedAmount($amount, $isoCode = null);

    /**
     * Specification:
     * - Retrieves a list of validators used to validate amount.
     *
     * @api
     *
     * @return array
     */
    public function getAmountValidators();
}
