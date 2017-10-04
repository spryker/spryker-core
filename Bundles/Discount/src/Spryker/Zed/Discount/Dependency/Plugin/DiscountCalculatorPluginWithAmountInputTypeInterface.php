<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Dependency\Plugin;

interface DiscountCalculatorPluginWithAmountInputTypeInterface
{

    /**
     * Specification:
     *  - With this interface for calculator plugin you can indicate what input type calculator expect.
     *  - For example it could be as defined in \Spryker\Shared\Discount\DiscountConstants (CALCULATOR_DEFAULT_INPUT_TYPE, CALCULATOR_MONEY_INPUT_TYPE).
     *
     * @api
     *
     * @return string
     */
    public function getInputType();

}
