<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Stability;

interface StabilityCalculatorInterface
{
    /**
     * @return array
     */
    public function calculateStability();
}
