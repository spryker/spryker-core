<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Calculator;

interface FloatRounderInterface
{
    /**
     * @param float $number
     *
     * @return int
     */
    public function round(float $number): int;
}
