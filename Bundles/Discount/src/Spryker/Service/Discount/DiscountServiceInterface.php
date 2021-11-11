<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Discount;

use Generated\Shared\Transfer\DiscountCalculationRequestTransfer;
use Generated\Shared\Transfer\DiscountCalculationResponseTransfer;

interface DiscountServiceInterface
{
    /**
     * Specification:
     * - Loops over `DiscountCalculatorPluginInterface` plugin stack to find suitable one by given plugin key.
     * - Executes applicable plugin in order to calculate discount price amount.
     * - Returns total calculated discount amount on given discountable items.
     * - Throws `DiscountCalculatorPluginNotFoundException` when suitable plugin is not found.
     *
     * @api
     *
     * @deprecated Please do not use this method. Exists only for internal purposes.
     *
     * @param \Generated\Shared\Transfer\DiscountCalculationRequestTransfer $discountCalculationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountCalculationResponseTransfer
     */
    public function calculate(
        DiscountCalculationRequestTransfer $discountCalculationRequestTransfer
    ): DiscountCalculationResponseTransfer;
}
